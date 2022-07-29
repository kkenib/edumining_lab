#!/usr/bin/env python3
# -*- coding: utf-8 -*-

# from elasticsearch5 import Elasticsearch
from edum_modules.module_configs import *


def text_loader(file_path, file_position="dir", data_no="", chapter_num=""):

    if file_position == "els":
        try:
            # es = Elasticsearch([{"host": els_host, "port": els_port}])
            els_body = {
                "query": {
                    "bool": {
                        "filter": [
                            {"match": {"idx": data_no}},
                            {"match": {"chapter": chapter_num}}
                        ]
                    }
                }
            }
            els_results = es.search(index=els_index, body=els_body)
            return_text = els_results['hits']['hits'][0]['_source']['text']
        except:
            return_text = ""
    else:
        try:
            f = open(file_path, 'r', encoding='utf-8')
            return_text = f.read()
        except:
            f = open(file_path, 'r')
            return_text = f.read()

    return return_text
