#!/usr/bin/env python3
# -*- coding: utf-8 -*-

# for remove stopwords

stopwords = [",", "!", "-", "...", "=", "(", ")", "[", "]", "-", "▼",
             "/", "★", "ⓒ", "^", "_", "<", ":", "`", "├", "─", "┼",
             "+", "”", "▲", "【", "】", "“", "&", "|", "·", "────────",
             "©", "‘", "’", "■", "•", "『", "』", "￮", "▷", "gt", "..",
             "◎", "ㆍ", "※", "ㅇ", "´", "# ", "♤", "℃", ".",
             "□", "%", "「", "」", ">", "◇", "…", "▶", "❍", "ㅜ",
             "¨", "●", "◦", "◁", "♪", "▨", "△", "♣", "◐",
             "〓", ".", "@", "'", "*", "~", "�", "◆", "ㅡ", "?",
             "↓", "–", ";", "#", "♥", "→", "˚", "∙ ", "ㅋ", "▣",
             "☞", "♬", "ㅎ", "ㅠ", "ㅅ", "♩", "Б", "◈", "{",
             "}", "☜", "☆", "♡", "～", "《", "》", "×", "‧", "aaa", "◀",
             "∼", "◀", "▽", "○", "»", "ㅣ", "▧", "☎", "☎", "↘", "OOOO", "⌒", "쁠",
             "℡", '"', "☆", "•", "ㅋ", "▲", "▼", "eeeeeeeeeetttttttttt", " ", "  "]

# mecab tags

# 'NNG-일반명사', 'NNP-고유명사', 'NNB-의존명사', 'NNBC-단위명사', 'SL-외국어', 'SH-한자', 'SN-숫자'
mecab_tag_n = ['NNG', 'NNP', 'NNB', 'NNBC', 'SL', 'SH', 'SN']
# 'VA-형용사'
mecab_tag_a = ['VA']
# 'VV-동사'
mecab_tag_v = ['VV']


# 절대경로
abs_path = "/home/theimc/edu_mining_flask/service_data/analysis/"
crawl_path = "/home/theimc/edu_mining_flask/crawler/data/"
upload_path = "/data3/edumining/upload/"
visual_path = "/home/theimc/edu_mining_flask/service_data/visual/"


# 엘라스틱 서버 호스트 / 포트
els_host = "221.157.125.36"
els_port = 9200
els_index = "edumining_data"

edum_host = "0.0.0.0"
edum_port = 2407

# webpage analysis table length
max_table_len = 200


from pymysql import OperationalError
from pymysqlpool.pool import Pool
pool = Pool(host='localhost', user='theimc', password='theimc#10!', db='edumining', autocommit=True)
pool.init()
def execute(query):
    conn = pool.get_conn()
    cursor = conn.cursor()
    cursor.execute(query)
    try:
        conn.commit()
    except OperationalError as err:
        print(err)
    pool.release(conn)
    return cursor

from elasticsearch import Elasticsearch
es = Elasticsearch([{'host': els_host, 'port': els_port}])

import time
def getCrawlFileName():
    now = time.localtime()
    uniq_file_name = str(now.tm_year) +"_"+ str(now.tm_mon) +"_"+ str(now.tm_mday) +"_"+ str(now.tm_hour) +"_"+ str(now.tm_min) +"_"+ str(now.tm_sec)
    fileName = crawl_path + uniq_file_name + "_naver_news.txt"
    return fileName