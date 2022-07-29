#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import pandas as pd
from edum_modules.module_configs import abs_path
from edum_modules.text_loader import text_loader
from collections import defaultdict

def analysis_transition(user_id, data_no, max_table_len):
    # table_list
    table_file_path = abs_path + user_id + "/" + str(data_no) + "/word_count/" + str(data_no) + "_table.txt"
    table_text = text_loader(table_file_path)

    table_text = table_text.split('\n')[1:-1]
    table_list = []
    for line in table_text:
        line_tmp = line.split(',')
        table_list.append([line_tmp[0], int(float(line_tmp[1])), float(line_tmp[2])])

    # visual_list
    visual_file_path = abs_path + user_id + "/" + str(data_no) + "/word_count/" + str(data_no) + "_visual.txt"
    visual_df = pd.read_csv(visual_file_path, encoding='utf-8').dropna(axis=0)
    visual_df = visual_df[:max_table_len].set_index('word')

    visual_list = []
    for col in visual_df.columns:
        tmp_dict = {"xvalue": col}
        tmp_dict.update(visual_df[col].to_dict())
        visual_list.append(tmp_dict)
    return table_list, visual_list
