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


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("-p", "--path2CSV", required=False,
                    help="path to CSV file")
    args = vars(ap.parse_args())
    path = os.path.join(project_root, 'PSAIDR/Data/DataSymptomsVec.csv')
    if len(args) > 0:
        if os.path.exists(str(args["path2CSV"])):
            path = args["path2CSV"]
        else:
            print("CSV file path {} dos not exists".format(str(args["path2CSV"])))
    wsp = set()
    t_model = gensim.models.Word2Vec.load(output_path)
    with open(path, 'r') as f:
        reader = csv.reader(f)
        ls = list(reader)
        for line in ls:
            for word in str(line[0]).split(' '):
                token1 = get_existed_tokens(clean_str(word), t_model)
                if len(token1)>0:
                    wsp.add(clean_str(word))
                    most_similar = t_model.wv.most_similar(token1, topn=50)
                    for sim in most_similar:
                        wsp.add(clean_str(sim[0]))
    with open(path[:-4] + 'most_similar.csv', 'w') as csvFile:
        writer = csv.writer(csvFile)
        for itm in wsp:
            writer.writerow([itm])

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
