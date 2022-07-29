#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import os
from edum_modules.text_loader import text_loader
from edum_modules.module_configs import abs_path


def replace_join_chapters(user_id, data_no, chapter_count, replace_word_dict):
    result_pos_text = ""

    for i in range(1, chapter_count + 1):

        pos_text_path = abs_path + user_id + "/" + str(data_no) + "/pos/" + str(data_no) + "_" + str(i) + ".txt"
        tmp_text = text_loader(pos_text_path)
        for key, value in replace_word_dict.items():
            tmp_text = tmp_text.replace(key, value)

        f = open(pos_text_path, 'w')
        f.write(tmp_text)
        f.close()

        result_pos_text += tmp_text

    merge_pos_text_path = abs_path + user_id + "/" + str(data_no) + "/pos/" + str(data_no) + "_replace.txt"
    f = open(merge_pos_text_path, 'w')
    f.write(result_pos_text)
    f.close()

    return result_pos_text
