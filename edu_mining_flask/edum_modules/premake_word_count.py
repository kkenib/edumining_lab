#!/usr/bin/env python3
# -*- coding: utf-8 -*-


import pandas as pd
import os
import operator
from collections import defaultdict
from edum_modules.text_loader import text_loader
from edum_modules.module_configs import abs_path


def premake_word_count(user_id, data_no, chapter_count):

    check_path = abs_path + user_id + "/" + str(data_no) + "/word_count"
    if user_id == "guest" and os.path.isdir(check_path):
        is_guest = True
    else:
        is_guest = False

    os.makedirs(abs_path+user_id+"/"+str(data_no)+"/word_count/", exist_ok=True)

    for i in range(1, chapter_count + 1):

        load_file_path = abs_path+user_id+"/"+str(data_no)+"/pos/"+str(data_no)+"_"+str(i)+".txt"

        text = text_loader(load_file_path)
        if len(text) > 0 and text[-1] == " ":
            text = text[:-1]

        word_list = text.split(" ")
        word_freq_dict = defaultdict()

        for word in word_list:
            if word in word_freq_dict:
                word_freq_dict[word] += 1
            else:
                word_freq_dict[word] = 1

        sorted_wfd = dict(sorted(word_freq_dict.items(), key=operator.itemgetter(1), reverse=True))

        cols = ["word", str(i)]
        tmp_table = pd.DataFrame(columns=cols, data=sorted_wfd.items())

        if i == 1:
            joined_table = pd.DataFrame(columns=cols, data=sorted_wfd.items())
        else:
            joined_table = pd.merge(joined_table, tmp_table, how='outer')

    joined_table = joined_table.fillna(0)
    joined_table['total'] = joined_table.sum(axis=1).astype('int')
    joined_table = joined_table.sort_values(by=['total'], axis=0, ascending=False)
    joined_table['per'] = (joined_table['total']/joined_table['total'].sum())*100

    col_table = ['word', 'total', 'per']

    if not is_guest:
        save_path_simple = abs_path + user_id + "/" + str(data_no) + "/word_count/" + str(data_no) + "_table.txt"
        joined_table[col_table].to_csv(save_path_simple, encoding='utf-8', index=False)

        save_path_visual = abs_path + user_id + "/" + str(data_no) + "/word_count/" + str(data_no) + "_visual.txt"
        joined_table.iloc[:, :-2].to_csv(save_path_visual, encoding='utf-8', index=False)

