#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import operator
import os
from collections import defaultdict
from edum_modules.module_configs import abs_path


def recommend_words(pos_text, user_id, data_no):
    # pos 원본 데이터
    word_list = pos_text.split()
    # ngram 사전 빈도수 사전 생성
    two_word_count_dict = defaultdict()
    for i in range(len(word_list) - 1):

        two_word = word_list[i] + '/' + word_list[i + 1]
        if two_word in two_word_count_dict:
            two_word_count_dict[two_word] += 1
        else:
            two_word_count_dict[two_word] = 1

    # 내림차순 정렬 후, 상위 1000개의 단어셋만 사용
    sorted_two_word_count_dict = dict(sorted(two_word_count_dict.items(), key=operator.itemgetter(1), reverse=True)[:1000])

    # 전/후 단어 리스트 생성
    word_front_dict = defaultdict()
    word_back_dict = defaultdict()
    for two_word, count in sorted_two_word_count_dict.items():
        word1, word2 = two_word.split("/")
        # word1이 앞에 등장 할 빈도
        if word1 in word_front_dict:
            word_front_dict[word1] += count
        else:
            word_front_dict[word1] = count
        # word2가 뒤에 등장 할 빈도
        if word2 in word_back_dict:
            word_back_dict[word2] += count
        else:
            word_back_dict[word2] = count

    # 빈도 확률을 통한 추천 단어 리스트 생성
    recommend_list = []
    for two_word, count in sorted_two_word_count_dict.items():
        if count == 1:
            break
        word1, word2 = two_word.split("/")
        word_front_rate = count / word_front_dict[word1]
        word_back_rate = count / word_back_dict[word2]
        average_rate = (word_front_rate + word_back_rate) / 2.0
        if average_rate >= 0.7:
            tmp_dict = {
                "before_words": word1 + " " + word2,
                "after_word": word1 + word2,
                "count": count
            }
            recommend_list.append(tmp_dict)

    if len(recommend_list) > 20:
        threshold_count = recommend_list[21]['count']
        recommend_list = recommend_list[:20]

        # 파일 저장
        recommend_file_path = abs_path+user_id+"/"+str(data_no)+"/pos/"+str(data_no)+"_recommend.txt"
        if user_id == "guest" and os.path.isfile(recommend_file_path):
            is_guest = True
        else:
            is_guest = False

        if not is_guest:
            f = open(recommend_file_path, 'w', encoding='utf-8')
        stop_idx = 20

        if is_guest:
            for i in range(len(recommend_list)):
                if recommend_list[i]['count'] == threshold_count:
                    stop_idx = i - 1
                    break
        else:
            for i in range(len(recommend_list)):
                if recommend_list[i]['count'] == threshold_count:
                    stop_idx = i-1
                    break
                f.write(recommend_list[i]["before_words"]+"\t"+recommend_list[i]["after_word"]+"\t"+str(recommend_list[i]["count"])+"\n")

        if not is_guest:
            f.close()

        recommend_list = recommend_list[:stop_idx]

    else:
        recommend_file_path = abs_path + user_id + "/" + str(data_no) + "/pos/" + str(data_no) + "_recommend.txt"
        if user_id == "guest" and os.path.isfile(recommend_file_path):
            is_guest = True
        else:
            is_guest = False

        if not is_guest:
            f = open(recommend_file_path, 'w', encoding='utf-8')
            for i in range(len(recommend_list)):
                f.write(recommend_list[i]["before_words"] + "\t" + recommend_list[i]["after_word"] + "\t" + str(
                    recommend_list[i]["count"]) + "\n")
            f.close()

    return recommend_list
