#!/usr/bin/env python3
# -*- coding: utf-8 -*-


from edum_modules.module_configs import stopwords


def pos_remove_stopwords(text):
    # remove punctuation
    for c in stopwords:
        text = text.replace(c, ' ')
    return str.join(' ', text.split())
