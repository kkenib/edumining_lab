#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from edum_modules.pos_remove_stopwords import pos_remove_stopwords
from edum_modules.pos_words_extraction import pos_words_extraction
from edum_modules.text_loader import text_loader
from edum_modules.module_configs import abs_path

import os


# 챕터 파일들을 순회하며 선택된 형태소들을 추출하여 유저 파일에 저장하고, 각 챕터의 형태소들을 조인하여 출력으로 보냄

def pos_join_chapters(user_id, data_no, chapter_count, tag_n_flag, tag_a_flag, tag_v_flag):
    result_pos_text = ""

    save_text_path = abs_path + user_id + "/" + str(data_no) + "/pos/"

    is_guest = False
    if user_id == "guest" and os.path.isdir(save_text_path):
        print("비회원 입니다.")
        is_guest = True

    for i in range(1, chapter_count + 1):
        text_tmp1 = text_loader("", "els", str(data_no), str(i))
        # 블용음절 삭제 & 형태소 추출
        text_tmp2 = pos_remove_stopwords(text_tmp1)
        text_tmp3, tags_ko = pos_words_extraction(text_tmp2, tag_n_flag, tag_a_flag, tag_v_flag)

        # 챕터별 데이터 저장
        if not is_guest:

            print(save_text_path + str(data_no) + "_" + str(i) + ".txt")
            print(chapter_count)

            os.makedirs(save_text_path, exist_ok=True)
            f = open(save_text_path + str(data_no) + "_" + str(i) + ".txt", 'w', encoding="utf-8")
            f.write(text_tmp3)
            f.close()

        result_pos_text += text_tmp3

        # 마지막 공백 삭제
    if len(result_pos_text) > 0 and result_pos_text[-1] == " ":
        result_pos_text = result_pos_text[:-1]


    if not is_guest:
        f = open(save_text_path + str(data_no) + "_pos.txt", 'w', encoding="utf-8")
        f.write(result_pos_text)
        f.close()

        f = open(save_text_path + str(data_no) + "_replace.txt", 'w', encoding="utf-8")
        f.write(result_pos_text)
        f.close()

        f = open(save_text_path + str(data_no) + "_speech.txt", 'w', encoding="utf-8")
        f.write(str(tags_ko))
        f.close()

    return result_pos_text
