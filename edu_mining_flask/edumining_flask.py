#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import json, threading, time, os, string, random, base64, sys
from flask import Flask, request, jsonify, json
from flask_cors import CORS
from edum_modules.pos_join_chapters import pos_join_chapters
from edum_modules.recommend_words import recommend_words
from edum_modules.replace_join_chapters import replace_join_chapters
from edum_modules.premake_word_count import premake_word_count
from edum_modules.analysis_word_count import analysis_word_count
from edum_modules.analysis_transition import analysis_transition
from edum_modules.analysis_associated_words import analysis_associated_words
from edum_modules.analysis_connected_network import analysis_connected_network
from edum_modules.analysis_sentiment import analysis_sentiment
from edum_modules.module_configs import *
from random import *
from datetime import datetime, timedelta
from dateutil.relativedelta import relativedelta
from elasticsearch import helpers
from crawler.naver_news import naver_news_crawl
from multiprocessing import Process
import pandas as pd
from distutils.dir_util import copy_tree

app = Flask(__name__)
app.config['SECRET_KEY'] = "theimc#1234!"
CORS(app)
CORS(app, resources={r'*': {"origins": "*"}})

@app.route('/pos', methods=['GET', 'POST'])
def pos_flask():
    user_id = request.form.get('user_id').strip()  # 유저 아이디
    data_no = request.form.get('data_no')  # 텍스트 파일 인덱스
    chapter_count = 1 if request.form.get('chapter_count') == '' else int(request.form.get('chapter_count'))   # 챕터 개수
    tag_n_flag = request.form.get('tag_n_flag')  # True/False
    tag_a_flag = request.form.get('tag_a_flag')  # True/False
    tag_v_flag = request.form.get('tag_v_flag')  # True/False

    if not user_id:
        user_id = "guest"

    pos_text = pos_join_chapters(user_id, data_no, chapter_count, tag_n_flag, tag_a_flag, tag_v_flag)
    recommend_list = recommend_words(pos_text, user_id, data_no)
    if user_id == "guest" and not os.path.isdir(abs_path + "/" + str(data_no) + "/word_count"):
        premake_word_count(user_id, data_no, chapter_count)
    # response -> text

    return_data = {
        "data": pos_text,
        "recommend_list": recommend_list
    }

    return jsonify(return_data)


@app.route('/replace', methods=['GET', 'POST'])
def replace_words_flask():
    user_id = request.form.get('user_id').strip()  # 유저 아이디
    data_no = request.form.get('data_no')  # 텍스트 파일 인덱스
    chapter_count = int(request.form.get('chapter_count'))  # 챕터 개수

    replace_word_dict = json.loads(request.form.get('replace_word_dict'))  # dict 타입

    if not user_id:
        user_id = "guest"

    response = replace_join_chapters(user_id, data_no, chapter_count, replace_word_dict)
    premake_word_count(user_id, data_no, chapter_count)

    # response -> text
    return_data = {
        "data": response
    }
    return jsonify(return_data)

@app.route('/copyToChildren', methods=['GET', 'POST'])
def copyToChild():
    userId = request.form.get('user_id').strip()  # 유저 아이디
    dataIdx = request.form.get('data_no')  # 텍스트 파일 인덱스

    return_data = { "is_success": False }

    # 교사 이상의 권한을 가진 사용자인지 확인
    query = f"SELECT mem_id FROM t_member WHERE mem_userid='{userId}' AND mem_level > 1"
    cursor = execute(query)
    result = cursor.fetchall()
    if len(result) == 0:
        return_data["is_success"] = False
        return_data["error_message"] = "사용자 검색 결과 없음"
        return jsonify(return_data)

    # 존재하는 데이터인지 확인
    query = f"SELECT no FROM edu_data_overview WHERE no={dataIdx} AND delete_status='N'"
    cursor = execute(query)
    result = cursor.fetchall()
    if len(result) == 0:
        return_data["is_success"] = False
        return_data["error_message"] = "데이터 검색 결과 없음"
        return jsonify(return_data)

    # t_member에서 해당 사용자(교사)의 학생에 해당하는 아이디 정보 가져오기
    query = f"SELECT mem_id, mem_userid FROM t_member WHERE mem_parent='{userId}' AND mem_parent IS NOT NULL AND mem_level=1"
    cursor = execute(query)
    searchedChildren = cursor.fetchall()

    # 교사가 정제한 추출물 저장하기(파일 덮어쓰기)
    fromDir = f"/home/theimc/edu_mining_flask/service_data/analysis/{userId}/{dataIdx}"
    for child in searchedChildren:
        userIdx, userId = child["mem_id"], child["mem_userid"]
        toDir = f"/home/theimc/edu_mining_flask/service_data/analysis/{userId}/{dataIdx}"
        try:
            copy_tree(fromDir, toDir)
            # print(f"Copy Dir User: {userId}, Dir: {toDir}")vr
        except Exception as e:
            print(e)

    # 교사가 정제한 추출물을 공유(분석)할 수 있도록 관련 레코드를 삽입함
    # 해당 데이터에 대한 레코드가 존재한다면 건너뜀
    childrenNo = []
    for child in searchedChildren:
        childrenNo.append(child["mem_id"])

    if len(childrenNo) > 0:
        childrenNoToStr = str(childrenNo).replace('[', '(').replace(']', ')')
        query = f"SELECT user_no FROM edu_data_artifact WHERE origin_no = {dataIdx} AND user_no IN {childrenNoToStr}"
        cursor = execute(query)
        result = cursor.fetchall()

        insertedChildenNo = []
        for child in result:
            insertedChildenNo.append(child["user_no"])
        
        # t_member - edu_data_artifact: 차집합으로 건너뛸 레코드 걸러냄
        childrenNo = list(set(childrenNo) - set(insertedChildenNo)) 

    children = []
    if len(childrenNo) > 0: # 목표하는 학생들의 아이디 정보를 가져옴
        childrenToNoStr = str(childrenNo).replace('[', '(').replace(']', ')')
        query = f"SELECT mem_id, mem_userid FROM t_member WHERE mem_id IN {childrenToNoStr} AND mem_denied=0"
        cursor = execute(query)
        children = cursor.fetchall()

    querySet = []
    for child in children: # INSERT Batch를 생성함
        userIdx, userId = child["mem_id"], child["mem_userid"]
        toDir = f"/home/theimc/edu_mining_flask/service_data/analysis/{userId}"
        querySet.append(f"\n({userIdx}, {dataIdx}, 0, 1, '', NOW())")

    query = "INSERT INTO edu_data_artifact(user_no, origin_no, anal_type, edit_step, file_path, update_date) VALUES "
    for i in range(0, len(querySet)): # 쿼리문을 만들어서 DB 삽입함
        query += querySet[i] + ('' if i == len(querySet) -1 else ',')

    if len(querySet) > 0:
        execute(query)

    return_data["is_success"] = True
    return jsonify(return_data)


@app.route('/count', methods=['GET', 'POST'])
def analysis_word_count_flask():
    user_id = request.form.get('user_id').strip()  # 유저 아이디
    data_no = request.form.get('data_no')  # 텍스트 파일 인덱스
    chapter = request.form.get('chapter').strip()

    if not user_id:
        user_id = "guest"

    response = analysis_word_count(user_id, data_no, chapter)
    if len(response) > max_table_len:
        response = response[:max_table_len]
    return json.dumps(response)


@app.route('/echo', methods=['GET', 'POST'])
def echo():
    return jsonify({'ECHO' : 'SUCCESS'})

    
@app.route('/transition', methods=['GET', 'POST'])
def analysis_transition_flask():
    user_id = request.form.get('user_id').strip()  # 유저 아이디
    data_no = request.form.get('data_no')  # 텍스트 파일 인덱스

    if not user_id:
        user_id = "guest"

    table_list, visual_list = analysis_transition(user_id, data_no, max_table_len)
    if len(table_list) > max_table_len:
        table_list = table_list[:max_table_len]

    response = {
        "table": table_list,
        "visual": visual_list
    }

    return json.dumps(response)


@app.route('/associate', methods=['GET', 'POST'])
def analysis_associated_words_flask():
    user_id = request.form.get('user_id').strip()  # 유저 아이디
    data_no = request.form.get('data_no')  # 텍스트 파일 인덱스
    chapter = request.form.get('chapter').strip()  # ex> "1 2 3"
    main_word = request.form.get('main_word').strip()  # 중심 단어
    window_size = int(request.form.get('window_size'))  # 동시 출현 범위

    if not user_id:
        user_id = "guest"

    response = analysis_associated_words(user_id, data_no, chapter, main_word, window_size)
    if len(response) > max_table_len:
        response = response[:max_table_len]

    return json.dumps(response)


@app.route('/connect', methods=['GET', 'POST'])
def analysis_connected_network_flask():
    user_id = request.form.get('user_id').strip()  # 유저 아이디
    data_no = request.form.get('data_no')  # 텍스트 파일 인덱스
    chapter = request.form.get('chapter').strip()  # ex> "1 2 3"
    window_size = int(request.form.get('window_size'))  # 동시 출현 범위

    if not user_id:
        user_id = "guest"

    response = analysis_connected_network(user_id, data_no, chapter, window_size)
    if len(response) > max_table_len:
        response = response[:max_table_len]
    return json.dumps(response)



@app.route('/sentiment', methods=['GET', 'POST'])
def analysis_sentiment_flask():
    user_id = request.form.get('user_id').strip()  # 유저 아이디
    data_no = request.form.get('data_no')  # 텍스트 파일 인덱스
    chapter = request.form.get('chapter').strip()  # ex> "1 2 3"

    if not user_id:
        user_id = "guest"

    positive_table, negative_table = analysis_sentiment(user_id, data_no, chapter)
    if len(positive_table) > max_table_len:
        positive_table = positive_table[:max_table_len]
    if len(negative_table) > max_table_len:
        negative_table = negative_table[:max_table_len]
    response = {
        "positive_table": positive_table,
        "negative_table": negative_table
    }
    return json.dumps(response)


@app.route('/runCrawl', methods=['POST'])
def runCrawl():
    try:
        json_data = json.loads(request.get_data(), encoding='utf-8')
        dataID = json_data['dataID']
    except:
        dataID = request.form['dataID']

    query = "select * from edu_data_overview where no = " + str(dataID)
    cursor = execute(query)

    eduData = cursor.fetchall()[0]

    keyword = eduData['collection_keyword']
    unit = eduData['collection_unit']
    startDate = eduData['collection_start_date']
    endDate = eduData['collection_end_date']
    site = eduData['collection_channel1']
    channel = eduData['collection_channel2']

    fileName = getCrawlFileName()

    day_delta = timedelta(days=1)
    month_delta = relativedelta(months=1)

    chpt_cnt = 1
    while 1:
        # TODO: 데이더 수집
        para_data = {
            "keyword" : keyword,
            "date" : startDate,
            "unit" : unit,
            "fileName" : fileName,
            "chapter" : chpt_cnt
        }

        naver_news_crawl(para_data)


        if unit == "D":
            startDate = startDate + day_delta

            if startDate > endDate:
                break
        elif unit == "M":
            startDate = startDate + month_delta

            if startDate > endDate:
                break
        chpt_cnt += 1

    nsize = os.path.getsize(fileName)
    file_size = nsize

    query = "update edu_data_overview set collection_state = 1, file_path = '" + fileName + "', data_size = " + str(file_size) + ", chapter_count = " + str(chpt_cnt) + " where no = " + dataID
    execute(query)

    return_data = {
        "msg" : "success",
        "dataID" : dataID,
        "fileName" : fileName
    }

    return json.dumps(return_data)


@app.route('/elasticUpdate', methods=['POST'])
def elasticUpdate():
    try:
        json_data = json.loads(request.get_data(), encoding='utf-8')
        idx = json_data['idx']
    except:
        idx = request.form['idx']

    query = "select * from edu_data_overview where no = " + str(idx)
    cursor = execute(query)

    eduData = cursor.fetchone()

    filename = eduData['file_path']
    dataName = eduData['data_name']
    fileData = open(filename, "r", encoding="utf-8").read().split("\n")[:-1]

    line_count = 0
    actions = []

    datadic = {}
    for line in fileData:
        linelist = line.split("\t")
        chapter = str(linelist[4])
        data = linelist[2]

        if chapter in datadic:
            datadic[chapter] = datadic[chapter] + " " + data
        else:
            datadic[chapter] = data

    for key in datadic.keys():
        actions.append({
            'idx'          : idx,
            'title'        : dataName,
            'text'         : datadic[key],
            'chapter'      : key,
            'kind'         : "naver",
            'subKind'      : "news"
        })

        helpers.bulk(es, actions, index="edumining_data", doc_type=str('channel'), request_timeout=30)
        line_count = 0
        actions = []

    # if os.path.isfile(filename):
        # os.remove(filename)

    return_data = {"msg" : "success"}
    return json.dumps(return_data)



@app.route('/elasticUpdateMyData', methods=['POST'])
def elasticUpdateMyData():

    def insertDataOverView(userNo, dataName, dataType, collectionState, chapterCount):
        query = f"INSERT INTO edu_data_overview(user_no, data_name, data_type, update_date, collection_state, chapter_count) "\
        f"VALUES ({userNo}, '{dataName}', {dataType}, NOW(), {collectionState}, {chapterCount})"
        cursor = execute(query)
        return cursor.lastrowid

    def updateTotalFileSize(insertedDataNo, filePath, totalSize):
        query = f"UPDATE edu_data_overview SET file_path='{filePath}', data_size={totalSize} WHERE no={insertedDataNo}"
        execute(query)

    def updateMyData(idx, dataName, fileData, chapter):
        actions = []
        actions.append({
            "idx"          : idx,
            "title"        : dataName,
            "text"         : fileData,
            "chapter"      : chapter,
            "kind"         : "upload",
            "subKind"      : "mine"
        })

        helpers.bulk(es, actions, index="edumining_data", doc_type=str('channel'), request_timeout=30)

    userNo          = request.form.get("user_no")
    dataName        = request.form.get("data_name")
    dataType        = request.form.get("data_type")
    collectionState = request.form.get("collection_state")
    chapterCount    = int(request.form.get("chapter_count"))
    insertedDataNo  = insertDataOverView(userNo, dataName, dataType, collectionState, chapterCount) if chapterCount > 0 else 0
    
    if insertedDataNo > 0:
        filePath = f"{upload_path}{insertedDataNo}"
        if not os.path.exists(filePath):
            os.mkdir(filePath)

        totalSize = 0
        for chapterNo in range(1, chapterCount + 1):
            encoded = request.form.get(f"file_{chapterNo}")
            decoded = base64.b64decode(encoded).decode('utf-8')
            updateMyData(insertedDataNo, dataName, decoded, chapterNo)
            totalSize += sys.getsizeof(decoded)
        updateTotalFileSize(insertedDataNo, filePath, totalSize)
    return json.dumps({"msg": "success" if insertedDataNo > 0 else "fail"})


@app.route('/getRawText', methods=['GET', 'POST'])
def getRawText():
    dataNo = request.form.get('data_no')  # 텍스트 파일 인덱스
    body = {
        "query": {
                "bool": {
                    "must": [
                        {
                            "match": {"idx": dataNo}
                        }

                    ]
                }
            },
        "sort": [
            {
                "chapter": {
                    "order": "asc"
                }
            }
        ]
    }

    result = es.search(index="edumining_data", body=body)
    hits = result["hits"]["hits"]
    text = ''
    
    for hit in hits:
        text += hit["_source"]["text"]
    return jsonify({"data": text})


@app.route('/getCleaningData', methods=['GET', 'POST'])
def getCleaningData():
    dataNo = request.form.get('data_no')  # 텍스트 파일 인덱스
    userId = request.form.get('user_id')  # 텍스트 파일 인덱스
    rawPath       = f"{abs_path}{userId}/{dataNo}/pos/{dataNo}_pos.txt"
    replacedPath  = f"{abs_path}{userId}/{dataNo}/pos/{dataNo}_replace.txt"
    recommandPath = f"{abs_path}{userId}/{dataNo}/pos/{dataNo}_recommend.txt"

    rawWords = ''
    if os.path.exists(rawPath):
        f = open(rawPath, 'r')
        while True:
            line = f.readline()
            if not line: break
            rawWords += line
        f.close()
        
    replacedWords = ''
    if os.path.exists(replacedPath):
        f = open(replacedPath, 'r')
        while True:
            line = f.readline()
            if not line: break
            replacedWords += line
        f.close()
    
    recommandWords = []
    if os.path.exists(recommandPath):
        f = open(recommandPath, 'r')
        while True:
            line = f.readline()
            if not line: break

            temp = line.split('\t')
            if len(temp) == 0: break

            recommandWords.append({
                "before": temp[0],
                "after": temp[1],
                "count": temp[2].replace('\n', '')})
        f.close()

    return jsonify({
        "raw_words": rawWords,
        "replace": replacedWords,
        "recommand": recommandWords
        })

@app.route('/getSpeechFile', methods=['GET', 'POST'])
def getSpeechFile():

    userId = request.form.get('user_id').strip()  # 유저 아이디
    dataNo = request.form.get('data_no')  # 텍스트 파일 인덱스
    speechPath       = f"{abs_path}{userId}/{dataNo}/pos/{dataNo}_speech.txt"

    exist = os.path.exists(speechPath)
    speechWords = ''
    if exist:
        f = open(speechPath, 'r')
        while True:
            line = f.readline()
            if not line: break
            speechWords += line
        f.close()

    return jsonify({
        "exist": exist,
        "file_size": len(speechWords),
        "speech": speechWords })

@app.route('/saveChartData', methods=['GET', 'POST'])
def saveChartData():
    result = False
    try:
        userId = request.form.get("user_id")
        chartData = request.form.get("chart_data")
        fileName = request.form.get("file_name")

        filePath = visual_path + userId
        if not os.path.exists(filePath):
            os.makedirs(filePath)

        filePath += f"/{fileName}" 

        f = open(filePath, 'w', encoding="utf-8")
        f.write(chartData)
        f.close()
        result = True
    except:
        result = False
    
    return jsonify({"result": result})


@app.route('/getVisualData', methods=['GET', 'POST'])
def getVisualData():
    userNo = request.form.get('no')

    visualData = []
    query = "select * from edu_data_artifact where no = " + str(userNo)
    cursor = execute(query)
    queryResult = cursor.fetchall()
    if len(queryResult) > 0:
        filePath = queryResult[0]["file_path"]
        exist = os.path.exists(filePath)
        if exist:
            f = open(filePath, 'r')
            while True:
                line = f.readline()
                if not line: break
                visualData.append(line.replace('\n', ''))
            f.close()
    
    return jsonify({"data": visualData})


@app.route('/getChartData', methods=['GET', 'POST'])
def getChartData():
    artNo = request.form.get('no')
    query = "select * from edu_data_artifact where no = " + str(artNo)
    cursor = execute(query)
    queryResult = cursor.fetchall()
    dataList  = ''
    chartData = None
    if len(queryResult) > 0:
        chartData = queryResult[0]
        filePath  = chartData["file_path"]
        exist = os.path.exists(filePath)
        if exist:
            f = open(filePath, 'r')
            while True:
                line = f.readline()
                if not line: break
                dataList += line
            f.close()
    return jsonify({"data_list": dataList, "chart_info": chartData})


@app.route('/getTextData', methods=['GET', 'POST'])
def getTextData():
    filePath = request.form.get('file_path')
    text = []
    exist = os.path.exists(filePath)
    if exist:
        f = open(filePath, 'r')
        while True:
            line = f.readline()
            if not line: break
            line = line.replace('\n', '')
            textArr = line.split('\t')
            text.append(textArr)
        f.close()
    return json.dumps({"text": text})

@app.route('/getCrawlData', methods=['GET', 'POST'])
def getCrawlData():
    filePath = request.form.get('file_path')
    text = []
    exist = os.path.exists(filePath)
    if exist:
        f = open(filePath, 'r')
        while True:
            line = f.readline()
            if not line: break
            line = line.replace('\n', '')
            textArr = line.split('\t')
            text.append(textArr[0: (len(textArr) -1)])
        f.close()
    return json.dumps({"text": text})


@app.route('/deleteVisualData', methods=['GET', 'POST'])
def deleteVisualData():
    try:
        artNo = request.form.get('no')
        query = "select * from edu_data_artifact where no = " + str(artNo)
        cursor = execute(query)
        queryResult = cursor.fetchall()
        if len(queryResult) > 0:
            filePath = queryResult[0]["file_path"]
            exist = os.path.exists(filePath)
            if exist:
                os.remove(filePath)
        
        result = True
    except:
        result = False
    
    return jsonify({"result": result})

@app.route('/deleteCrawlData', methods=['GET', 'POST'])
def deleteCrawlData():
    json_data = json.loads(request.get_data(), encoding='utf-8')
    filePath = json_data['file_path']
    exist = os.path.exists(filePath)
    if exist:
        os.remove(filePath)
    
    return_data = {"msg" : "success"}
    return json.dumps(return_data)

if __name__ == '__main__':
    app.run(host=edum_host, port=edum_port, debug=False, ssl_context=('/etc/letsencrypt/live/edumining.textom.co.kr/cert.pem', '/etc/letsencrypt/live/edumining.textom.co.kr/privkey.pem'))
    # app.run(host=edum_host, port=edum_port, debug=False)
