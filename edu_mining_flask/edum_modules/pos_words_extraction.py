#!/usr/bin/env python3
# -*- coding: utf-8 -*-


from konlpy.tag import Mecab

from edum_modules.module_configs import mecab_tag_n, mecab_tag_a, mecab_tag_v


def pos_words_extraction(text, tag_n_flag=False, tag_a_flag=False, tag_v_flag=False, with_tag=False):
    new_text = ""  # return string init
    # 선택 태크 리스트 만들기
    mecab_tags = []
    if tag_n_flag == "true":
        mecab_tags += mecab_tag_n
    if tag_a_flag == "true":
        mecab_tags += mecab_tag_a
    if tag_v_flag == "true":
        mecab_tags += mecab_tag_v

    mecab = Mecab()
    tags_ko = mecab.pos(text)  # [(단어1, 태그1), (단어2, 태그2), ... ]

    # 선택 형태소 문자열 생성
    if with_tag:
        for word, tag in tags_ko:
            new_text += word + "/" + tag + " "
    else:
        for word, tag_k in tags_ko:
            if tag_k in mecab_tags:
                new_text += word + " "

    return new_text, tags_ko
