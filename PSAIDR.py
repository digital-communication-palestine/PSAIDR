# -*- coding: utf-8 -*-
# !/usr/bin/python3.7

import argparse
import gensim
import re
import os
import numpy as np
import csv
import collections

project_root = os.path.dirname(os.path.dirname(__file__))
output_path = os.path.join(project_root, 'PSAIDR/models/full_uni_sg_100_twitter.mdl')

with open(os.path.join(project_root, 'PSAIDR/Data/DataSymptomsVecmost_similar.csv'), 'r') as f:
    reader = csv.reader(f)
    most_similar = list(reader)


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("-v", "--vector", required=False,
                    help="String to be vectorized")
    args = vars(ap.parse_args())
    if args["vector"] is None:
        args["vector"] = "مبارح بالليل اجتني حرارة و كحة "
        args["vector"] = "مناخيري و راسي بيجعني"
    path = os.path.join(project_root, 'PSAIDR/Data/DataSymptomsVec.csv')
    symp = {}
    wsp = {}
    with open(path, 'r') as f:
        reader = csv.reader(f)
        ls = list(reader)
        for line in ls:
            symp[line[0]] = np.asarray(line[1:], dtype=np.float32)
            wsp[line[0]] = 0
    t_model = gensim.models.Word2Vec.load(output_path)
    token1 = get_existed_tokens(clean_str(str(args["vector"])), t_model)
    # token1 = [x for x in token1 if x  in most_similar]
    try:
        """
        word_vector = np.asarray(t_model.wv[token1], dtype=np.float32)
        if word_vector.shape[0] > 1:
            for vec in word_vector:
                for key in symp:
                    wsp[key] = wsp[key] + cosine_similarity(symp[key], vec)
        else:
            for key in symp:
                wsp[key] = wsp[key] + cosine_similarity(symp[key], np.asarray(word_vector, dtype=np.float32))
         """
        word_vector = np.average(np.asarray(t_model.wv[token1], dtype=np.float32), axis=0)
        for key in symp:
            wsp[key] = cosine_similarity(symp[key], word_vector)
        sorted_x = sorted(wsp.items(), key=lambda kv: kv[1])
        print(collections.OrderedDict(sorted_x[:]))
        for key in collections.OrderedDict(sorted_x[-5:]).__reversed__():
            print(key)
            print(symp[key])

    except:
        print('Error')

    return None


# =========================
# ==== Helper Methods =====

# Clean/Normalize Arabic Text
def cosine_similarity(a, b):
    dot = np.dot(a.flat, b.flat)
    norma = np.linalg.norm(a)
    normb = np.linalg.norm(b)
    cos = dot / (norma * normb)
    return cos


def clean_str(text):
    C = ["ئ", " ", "ء", "ؤ", "لآ", "آ", "ر", "لا", "ى", "ة", "و", "ز", "ظ", "ش", "س", "ي", "ب", "ل", "ا", "لأ", "أ",
         "لإ", "إ", "ت", "ن", "م", "ك", "ط", "ض", "ص", "ث", "ق", "ف", "غ", "ع", "ه", "خ", "ح", "ج", "د", "ذ"]
    rx = '[^' + re.escape(''.join(C)) + ']'
    text = re.sub(rx, ' ', text)
    search = ["أ", "إ", "آ", "ة", "_", "-", "/", ".", "،", " و ", " يا ", '"', "ـ", "'", "ى", "\\", '\n', '\t',
              '&quot;', '?', '؟', '!']
    replace = ["ا", "ا", "ا", "ه", " ", " ", "", "", "", " و", " يا", "", "", "", "ي", "", ' ', ' ', ' ', ' ? ', ' ؟ ',
               ' ! ']

    # remove tashkeel
    p_tashkeel = re.compile(r'[\u0617-\u061A\u064B-\u0652]')
    text = re.sub(p_tashkeel, "", text)

    # remove longation
    p_longation = re.compile(r'(.)\1+')
    subst = r"\1\1"
    text = re.sub(p_longation, subst, text)

    text = text.replace('وو', 'و')
    text = text.replace('يي', 'ي')
    text = text.replace('اا', 'ا')

    for i in range(0, len(search)):
        text = text.replace(search[i], replace[i])

    # trim
    text = text.strip()

    return text


def get_existed_tokens(tokens, n_model):
    return [tok for tok in tokens.split(' ') if tok in n_model.wv]


if __name__ == '__main__':
    main()
