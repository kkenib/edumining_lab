#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import numpy as np
import operator
import pandas as pd
from collections import defaultdict
from edum_modules.merge_and_wordlist import merge_and_wordlist


def analysis_associated_words(user_id, data_no, chapter, main_word, window_size):
    word_list = merge_and_wordlist(user_id, data_no, chapter)
    word_dict = defaultdict()

    # numpy 를 활용하여 단어의 인덱스 추출
    word_np = np.array(word_list)
    main_word_index = np.where(word_np == main_word)

    # 중심 단어의 인덱스를 중점 반복
    for idx in main_word_index[0]:
        # 시작 인덱스와 종료 인덱스를 설정
        if idx - window_size < 0:
            start_idx = 0
        else:
            start_idx = idx - window_size

        if idx + window_size > len(word_list)-1:
            end_idx = len(word_list)-1
        else:
            end_idx = idx + window_size

        # 중심단어는 제외
        for word in word_list[start_idx:end_idx+1]:
            if word == main_word:
                continue
            if word in word_dict:
                word_dict[word] += 1
            else:
                word_dict[word] = 1

    sorted_wd = dict(sorted(word_dict.items(), key=operator.itemgetter(1), reverse=True))

    # 리턴 테이블 생성
    cols = ["word", "count"]
    word_count_table = pd.DataFrame(columns=cols, data=sorted_wd.items())
    word_count_table["count"].astype('int')
    word_count_table["per"] = (word_count_table["count"] / word_count_table["count"].sum()) * 100

    return word_count_table.values.tolist()

