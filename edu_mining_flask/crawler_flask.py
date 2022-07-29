#!/usr/bin/env python
# -*- coding: utf-8 -*-
from flask import *
from flask_cors import CORS
from random import *
from datetime import datetime, timedelta
from dateutil.relativedelta import relativedelta
from elasticsearch import Elasticsearch
from elasticsearch import helpers
from crawler.naver_news import naver_news_crawl
from pymysqlpool.pool import Pool
import os, time, threading
import pandas as pd
from multiprocessing import Process
from edum_modules.module_configs import *

app = Flask(__name__)
app.config['SECRET_KEY'] = "theimc#1234!"
CORS(app)
CORS(app, resources={r'*': {"origins": "*"}})

def crawlProcess(eduData):
    dataID = eduData['no']
    keyword = eduData['collection_keyword']
    unit = eduData['collection_unit']
    startDate = eduData['collection_start_date']
    endDate = eduData['collection_end_date']
    site = eduData['collection_channel1']
    channel = eduData['collection_channel2']

    fileName = getCrawlFileName()
    query = "update edu_data_overview set file_path = '" + fileName + "' where no = " + str(dataID)
    execute(query)

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

    query = "update edu_data_overview set collection_state = 1, data_size = " + str(file_size) + ", chapter_count = " + str(chpt_cnt) + " where no = " + str(dataID)
    execute(query)

def crawlMonit(eduData):
    dataID = eduData['no']
    while True:
        query = f"select * from edu_data_overview where no = {dataID}"
        cursor = execute(query)
        queryResult = cursor.fetchall()
        if len(queryResult) > 0:    
            collectionState = queryResult[0]["collection_state"]
            if collectionState == 1: break
            filePath = queryResult[0]["file_path"]
            if filePath is not None and os.path.exists(filePath):
                fileSize = os.path.getsize(filePath)
                query = f"update edu_data_overview set data_size = {fileSize} where no = {dataID}"
                execute(query)
        time.sleep(5)


def uploadRun():
    query = "select * from edu_data_overview where collection_state = 0 and data_type = 2"
    while 1:
        cursor = execute(query)
        dataList = cursor.fetchall()

        for data in dataList:
            pathDir = upload_path + str(data['no'])
            file_list = os.listdir(pathDir)

            for upfile in file_list:
                fPath = pathDir + "/" + upfile
                fileExt = fPath.split(".")[-1]
                actions = []

                if fileExt == "txt":
                    filetxt = open(fPath, "r", encoding="utf-8").read()

                    actions.append({
                        'idx'          : data['no'],
                        'title'        : data['data_name'],
                        'text'         : filetxt,
                        'chapter'      : int(upfile.split(".")[0].replace("chapter", "")),
                        'kind'         : "upload",
                    })

                    helpers.bulk(es, actions, index="edumining_data", doc_type=str('storage'), request_timeout=30)
                elif fileExt == "xls" or fileExt == "xlsx":
                    try:
                        df = pd.read_excel(fPath, engine='openpyxl', header=None)
                    except:
                        df = pd.read_excel(fPath, header=None)

                    ar = df.to_numpy()

                    filetxt = ""
                    for line in ar:
                        tmp = " ".join(map(str, line)).replace("nan", "").strip()
                        filetxt = filetxt + tmp + "\n"

                    actions.append({
                        'idx'          : data['no'],
                        'title'        : data['data_name'],
                        'text'         : filetxt,
                        'chapter'      : int(upfile.split(".")[0].replace("chapter", "")),
                        'kind'         : "upload",
                    })

                    helpers.bulk(es, actions, index="edumining_data", doc_type=str('storage'), request_timeout=30)

            query = "update edu_data_overview set collection_state = 1 where no = " + str(data['no'])
            execute(query)
        time.sleep(10)

def dataCrawl():
    execDataNoList = []
    while 1:
        query = "select * from edu_data_overview where collection_state = 0 and data_type = 1"
        cursor = execute(query)
        eduDataList = cursor.fetchall()

        selectedDataNoList = []
        for eduData in eduDataList:
            no = eduData['no']
            selectedDataNoList.append(no)
        execDataNoList = list(set(execDataNoList) & set(selectedDataNoList))

        if len(execDataNoList) >= 5:
            time.sleep(5)
            continue    

        for eduData in eduDataList:
            no = eduData['no']
            if no not in execDataNoList:
                execDataNoList.append(no)
                p1 = Process(target=crawlProcess, args=(eduData,))
                p1.start()
                p2 = Process(target=crawlMonit, args=(eduData,))
                p2.start()
        time.sleep(5)

if __name__ == "__main__":
    task1 = threading.Thread(target=uploadRun)
    task2 = threading.Thread(target=dataCrawl)
    task1.start()
    task2.start()

