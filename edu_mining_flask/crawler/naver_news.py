#!/usr/bin/env python
#-*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        naver_news
# Purpose:
# Version:     Python 3.6
# Author:      Jeong Seong Uk
#
# Created:     10-11-2021
# Copyright:   (c) JSU 2021
# Licence:     <JSU>
#-------------------------------------------------------------------------------
from bs4 import BeautifulSoup
import os
import time
from datetime import datetime, timedelta
import requests
import urllib
import re
import calendar
import pymysql
#import MySQLdb
#import settings
#from elasticsearch import Elasticsearch
#from elasticsearch import helpers

# 네이버 뉴스 주소 생성
def getUrl(keyword, date_start='', date_end='', start=1):
    date_start = date_start.replace("-",".")
    date_end = date_end.replace("-",".")

    if date_start == '' or date_end == '':
        url = "https://search.naver.com/search.naver?where=news&query=" + str(keyword) + "&sm=tab_opt&sort=1&start=" + str(start)
    else:
        url = "https://search.naver.com/search.naver?where=news&query=" + str(keyword) + "&sm=tab_opt&sort=1&photo=0&field=0&reporter_article=&pd=3&ds=" + str(date_start) + "&de=" + str(date_end) + "&docid=&nso=so%3Add%2Cp%3A1w%2Ca%3Aall&mynews=0&refresh_start=0&start=" + str(start) + "&related=0"

    return url


# html 가져오기
def get_page(url):
    time.sleep(2)
    header = {}
    header['User-Agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.118 Whale/2.11.126.23 Safari/537.36'
    header['Accept-Language'] = 'ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7'
    request = requests.get(url, headers=header)
    html = request.text
    return html


# 파일 생성
def createFile():
    now = time.localtime()
    uniq_file_name = str(now.tm_year) +"_"+ str(now.tm_mon) +"_"+ str(now.tm_mday) +"_"+ str(now.tm_hour) +"_"+ str(now.tm_min) +"_"+ str(now.tm_sec)
    #file_path ="/home/theimc/crawler/data/file_naver_data/"+uniq_file_name
    file_path = "/home/theimc/edumining_crawler/crawler/data/" + uniq_file_name
    out_file = open(file_path+"_naver_news.txt", "w", encoding="utf-8")

    return out_file


# 네이버 뉴스 수집
def naver_news_crawl(data):
    keyword = data['keyword']
    unit = data['unit']
    fileName = data['fileName']
    chpt_cnt = data['chapter']

    if unit == "D":
        date_start = str(data['date'])
        date_end = date_start
        write_date = date_start
    elif unit == "M":
        date_list = str(data['date']).split("-")
        endDay = calendar.monthrange(int(date_list[0]), int(date_list[1]))[1]
        date_start = date_list[0] + "-" + date_list[1] + "-01"
        date_end = date_list[0] + "-" + date_list[1] + "-" + str(endDay)

        tmp = datetime(int(date_end.split("-")[0]), int(date_end.split("-")[1]), int(date_end.split("-")[2]))
        if tmp > datetime.now():
            #print("up")
            date_end = datetime.now() - timedelta(days=1)
            date_end = str(date_end).split(" ")[0]
        write_date = "-".join(date_start.split("-")[:-1])


    print(date_start + " to " + date_end)
    writeFile = open(fileName, "a", encoding="utf-8")
    #writeFile.write(date_start + "\t============================================\n")
    parseKeyword = urllib.parse.quote_plus(keyword)
    start = 1

    crawl_count = 0
    total_count = 100
    #create_file_name = out_file.name
    while_break = False

    while 1:
        if start > total_count:
            print("page over 10")
            break
        url = getUrl(parseKeyword, date_start, date_end, start)

        html = get_page(url)

        soup = BeautifulSoup(html, "html.parser", from_encoding="utf-8")
        news_list = soup.select('.list_news > .bx')
        news_count = len(news_list)
        if news_count == 0:
            print("search news count is 0")
            break

        for news in news_list:
            time.sleep(1)
            if crawl_count >= 100:
                while_break = True
                print("crawl news count is 100 over")
                break
            try:
                title = news.select_one('.news_tit').text
            except:
                title = ""
            try:

                contents = news.select_one('.dsc_txt_wrap').text
            except:
                contents = ""
            try:
                link = news.select_one('.news_tit')['href']
            except:
                link = ""
            """
            if link != "":
                try:
                    #news_html = requests.get(link)
                    news_request = get_page(link)

                    body = kstrip(news_request)
                    body = re.sub('<.*?>', '', str(body))
                    body = body.replace("&nbsp;", "").strip()
                    body = str(body).replace("&gt;","").strip()
                    body = str(body).replace(">","").strip()
                    body = body.replace("  ", "").strip()

                    txt_list = body.split(" ")
                    test_txt = []
                    for txt in txt_list:
                        if str(txt).strip().count("http://") > 0 or str(txt).strip().count("https://") > 0 or str(txt).strip().count("null") > 0 or str(txt).strip().count("[]") > 0 or str(txt).strip().count("||") > 0 or str(txt).strip().count("jQuery") > 0 :
                            continue
                        test_txt.append(str(txt).strip())
                    body = " ".join(test_txt)
                except:
                    body = ""
            """
            try:
                writeFile.write(title + '\t' + link + '\t' + contents + '\t' + write_date + '\t' + str(chpt_cnt) + '\n')
                crawl_count = crawl_count + 1
            except:
                continue
        print(crawl_count)
        if crawl_count == 0:
            break
        if while_break == True:
            break

        start = start + 10
    writeFile.close()



    #elasticsearchConn(idx_num, count_web, keyword, create_file_name)


# DB 접속 세팅
def dbConn():
    try:
        conn  = pymysql.connect(user='theimc',passwd='theimc#10!', host='localhost', db='edumining', cursorclass=pymysql.cursors.DictCursor)
    except Exception as e:
        conn  = pymysql.connect(user='theimc',passwd='theimc#10!', host='localhost', db='edumining', cursorclass=pymysql.cursors.DictCursor)

    return conn

# 엘라스틱 서치 데이터 Insert
def elasticsearchConn(idx_num, count_web, keyword, creat_file_name):
    es1 = Elasticsearch([{'host': '221.157.125.36', 'port': 9200}])


    conn = dbConn()
    cursor = conn.cursor()
    cursor.execute("SELECT keyword_list_idx from scraw_naver_datainfo where idx ='"+idx_num+"'")
    row2 = cursor.fetchone()
    out_file = open(creat_file_name, "r")
    actions = []

    for lines in out_file:
        line = lines.split("\t")

        try :
            title = line[0].strip().encode("utf-8")
        except:
            title = ""
        try:
            url = line[1].strip()
        except:
            url = ""
        try :
            text = line[2].strip().encode("utf-8")
        except:
            text = ""
        try :
            body_text = line[3].strip().encode("utf-8")
        except:
            body_text = ""

        actions.append({
            'idx'          : str(row2[0]).strip(),
            'kind'         : str("naver").strip(),
            'subKind'      : str("news").strip(),
            'title'        : title.strip(),
            'url'          : url.strip(),
            'text'         : text.strip(),
            'body_text'    : body_text.strip()
        })
        try:
            if len(actions) > 100:
                helpers.bulk(es1, actions, index="textom_data", doc_type=str('channel'), request_timeout=30)
                actions = []
        except:
            pass
    try :
        helpers.bulk(es1, actions, index="textom_data", doc_type=str('channel'), request_timeout=30)
    except:
        pass

    out_file.close()

    cursor.execute("UPDATE scraw_naver_datainfo SET news_countnum_craw='"+str(count_web)+"' WHERE idx= '"+ idx_num +"'")
    conn.commit()

    try :
        nsize = os.path.getsize(creat_file_name)
        cursor.execute("SELECT user_id,date_start,date_end from keyword_list where idx ='"+str(row2[0])+"'")
        row_user = cursor.fetchone()
        file_size = float(nsize/1024)
        cursor.execute("insert into scrw_use_size (keyword_idx,keyword,user_id,channel,sub_channel,scrw_size,start_date,end_date,site_kind) values \
                        ('"+str(row2[0])+"','"+conn.escape_string(str(keyword))+"','"+str(row_user[0])+"','naver','news','"+str(file_size)+"','"+str(row_user[1])+"','"+str(row_user[2])+"','channel')")
        conn.commit()
        os.remove(creat_file_name)
    except :
        pass

    cursor.close()
    conn.close()

if __name__ == "__main__":
    naver_news_crawl("독감", "123123", date_start='2020-11-10', date_end='2020-11-11')
