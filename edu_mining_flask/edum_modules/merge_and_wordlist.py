#!/usr/bin/env python3
# -*- coding: utf-8 -*-


from edum_modules.text_loader import text_loader
from edum_modules.module_configs import abs_path
from edum_modules.pos_words_extraction import pos_words_extraction


def merge_and_wordlist(user_id, data_no, chapter, with_tag=False):
    merged_text = ""

    chapters = chapter.split()
    for chap in chapters:
        if with_tag:
            tmp_text = text_loader("", "els", str(data_no), chap)
        else:
            load_file_path = abs_path + user_id + "/" + str(data_no) + "/pos/" + str(data_no) + "_" + chap + ".txt"
            tmp_text = text_loader(load_file_path)
        merged_text += tmp_text

    if merged_text[-1] == " ":
        merged_text = merged_text[:-1]

    if with_tag:
        word_list, _ = pos_words_extraction(merged_text, False, False, False, True)
    else:
        word_list = merged_text

    return word_list.split(" ")
