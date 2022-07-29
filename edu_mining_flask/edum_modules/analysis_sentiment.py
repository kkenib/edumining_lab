#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import pandas as pd
import operator
from collections import defaultdict
from edum_modules.merge_and_wordlist import merge_and_wordlist


def analysis_sentiment(user_id, data_no, chapter):

    # 감정사전 불러오기
    positive_sentiment = pd.read_csv("/home/theimc/edu_mining_flask/edum_modules/positive_sentiment_dict.csv", encoding="utf-8")
    negative_sentiment = pd.read_csv("/home/theimc/edu_mining_flask/edum_modules/negative_sentiment_dict.csv", encoding="utf-8")
    positive_sentiment = positive_sentiment.set_index('pos')
    negative_sentiment = negative_sentiment.set_index('pos')

    # 테이블 초기값 설정
    positive_dict = defaultdict()
    negative_dict = defaultdict()

    # 선택된 챕터를 결합하고 단어를 순회하며 감정단어 카운트
    word_pos_list = merge_and_wordlist(user_id, data_no, chapter, with_tag=True)
    
    for word_pos in word_pos_list:
        if word_pos in positive_sentiment.index:
            positive_word = positive_sentiment.loc[word_pos].word
            if positive_word in positive_dict:
                positive_dict[positive_word] += 1
            else:
                positive_dict[positive_word] = 1
        elif word_pos in negative_sentiment.index:
            negative_word = negative_sentiment.loc[word_pos].word
            if negative_word in negative_dict:
                negative_dict[negative_word] += 1
            else:
                negative_dict[negative_word] = 1

    # 사전 형태를 리스트로 변경
    positive_table = sorted(positive_dict.items(), key=operator.itemgetter(1), reverse=True)
    negative_table = sorted(negative_dict.items(), key=operator.itemgetter(1), reverse=True)

    return positive_table, negative_table
