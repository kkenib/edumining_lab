#!/usr/bin/env python3
# -*- coding: utf-8 -*-


from timeit import repeat
from numpy import dtype
import pandas as pd
import operator, os
from edum_modules.merge_and_wordlist import merge_and_wordlist
from datetime import datetime
from multiprocessing import Pool

def elapsed(start_time):
    end_time = datetime.now()
    elapsed_time = end_time - start_time
    print(elapsed_time)


def countWordFrequency(startIndex, endIndex, window_size, word_list, wm):
    for i in range(startIndex, endIndex):
        if i - window_size < 0:
            start_idx = 0
        else:
            start_idx = i - window_size

        if i + window_size > len(word_list) - 1:
            end_idx = len(word_list) - 1
        else:
            end_idx = i + window_size

        for word in word_list[start_idx:end_idx + 1]:
            wm.loc[word_list[i], word] += 1
    return wm


def aggregateResult(startIndex, endIndex, wordUnique, wordMatrix):
    resultTable = []
    total = 0
    for i in range(startIndex, endIndex):
        for j in range(i+1, len(wordUnique)):
            if wordMatrix.loc[wordUnique[i], wordUnique[j]] > 0:
                resultTable.append([wordUnique[i], wordUnique[j], int(wordMatrix.loc[wordUnique[i], wordUnique[j]])])
                total += wordMatrix.loc[wordUnique[i], wordUnique[j]]

    return (total, resultTable)


def analysis_connected_network(user_id, data_no, chapter, window_size):

    start_time = datetime.now()
    word_list = merge_and_wordlist(user_id, data_no, chapter)
    elapsed(start_time)

    word_unique = list(set(word_list))
    word_matrix = pd.DataFrame(pd.DataFrame(data=0, columns=word_unique, index=word_unique))
    

    # 텍스트 분석. 멀티프로세싱 풀 처리 시작
    totalItemCount = len(word_list)
    repeatCount = 8 #os.cpu_count()
    unitItemCount = int(totalItemCount/repeatCount)
    restItemCount = totalItemCount - unitItemCount * repeatCount
    
    workList = []
    for k in range(0, repeatCount): # 병렬처리를 위한 작업 분할
        startIndex = unitItemCount * k
        endIndex   = startIndex + unitItemCount
        work = [startIndex, endIndex, window_size, word_list, word_matrix.copy()]
        workList.append(work)
        
    start_time = datetime.now()
    p = Pool(repeatCount)
    pResult = p.starmap(countWordFrequency, workList)
    procMatrixList = pResult if len(pResult) > 0 else [] # 결과 바인딩

    if restItemCount > 0:   # 나머지 시리즈(Series) 처리. 잔여 작업 처리
        startIndex = unitItemCount * repeatCount
        endIndex   = totalItemCount
        res = countWordFrequency(startIndex, endIndex, window_size, word_list, word_matrix.copy())
        procMatrixList.append(res)

    tmpMatrix = None
    for wm in procMatrixList:   # 결과 합치기 => DataFrame 단어 빈도 합산
        tmpMatrix = wm if tmpMatrix is None else (tmpMatrix + wm)
    word_matrix = tmpMatrix
    elapsed(start_time)
    ###### 텍스트 분석. 멀티프로세싱 풀 처리 끝


    start_time = datetime.now()
    result_table = []
    total_sum = 0

    # 결과 취합. 멀티프로세싱 풀 처리 시작
    totalItemCount = len(word_unique) - 1
    repeatCount = 8 #os.cpu_count()
    unitItemCount = int(totalItemCount/repeatCount)
    restItemCount = totalItemCount - unitItemCount * repeatCount

    workList = []
    for i in range(0, repeatCount): # 병렬처리를 위한 작업 분할
        startIndex = unitItemCount * i
        endIndex   = startIndex + unitItemCount
        work = [startIndex, endIndex, word_unique, word_matrix]
        workList.append(work)

    start_time = datetime.now()
    p = Pool(repeatCount)
    pResult = p.starmap(aggregateResult, workList)
    resultList = pResult if len(pResult) > 0 else [] # 결과 바인딩

    if restItemCount > 0:   # 나머지 시리즈(Series) 처리. 잔여 작업 처리
        startIndex = unitItemCount * repeatCount
        endIndex   = totalItemCount
        res = aggregateResult(startIndex, endIndex, word_unique, word_matrix)
        resultList.append(res)

    for result in resultList:   # 프로세스 결과 취합. aggregate
        total, resTable = result[0], result[1]
        total_sum += total
        result_table = result_table + resTable
    
    ###### 결과 취합. 멀티프로세싱 풀 처리 끝

    start_time = datetime.now()
    response = sorted(result_table, key=operator.itemgetter(2), reverse=True)
    for i in range(len(response)):
        response[i].append((response[i][-1]/total_sum)*100)
    elapsed(start_time)

    return response
