#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import pandas as pd
import operator
from collections import defaultdict
from edum_modules.merge_and_wordlist import merge_and_wordlist


def analysis_word_count(user_id, data_no, chapter):
    word_list = merge_and_wordlist(user_id, data_no, chapter)

    word_freq_dict = defaultdict()

    for word in word_list:
        if word in word_freq_dict:
            word_freq_dict[word] += 1
        else:
            word_freq_dict[word] = 1

    sorted_wfd = dict(sorted(word_freq_dict.items(), key=operator.itemgetter(1), reverse=True))

    cols = ["word", "count"]
    word_count_table = pd.DataFrame(columns=cols, data=sorted_wfd.items())
    word_count_table["count"].astype('int')
    word_count_table["per"] = (word_count_table["count"] / word_count_table["count"].sum()) * 100

    return word_count_table.values.tolist()
