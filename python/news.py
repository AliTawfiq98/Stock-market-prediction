import requests
import json
import pandas as pd
import sys
from nltk import word_tokenize, re
from nltk.corpus import stopwords
import csv

import textblob as t

from datetime import date
import dateutil.relativedelta
now = date.today()
prev_month = now + dateutil.relativedelta.relativedelta(months=-1)

ticker = sys.argv[1]
key = '{} stock'.format(ticker)
# key='APPL stock'
url = ('http://newsapi.org/v2/everything?'
       'q={}&'
       'from={}'
       'sortBy=popularity&'
       'apiKey=b7de039e294740bb84d8dff8c2bbf97d').format(key, prev_month)

response = requests.get(url)
response_json_string = json.dumps(response.json())
response_dict = json.loads(response_json_string)
articles_list = response_dict['articles']

df = pd.read_json(json.dumps(articles_list))
df.to_csv('C:\\xampp\\htdocs\\SMP\\viewer\\sentiments\\articles.csv')


def analyze_sentiment(news):
    analysis = t.TextBlob(news)
    if analysis.sentiment.polarity > 0:
        return "positive"
    if analysis.sentiment.polarity == 0:
        return "neutral"
    else:
        return "negative"

def clean_news(news):
    output_news = []
    filtered_sentence = []
    x = ' '.join(re.sub('(@[A-Za-z0-9]+)|([^0-9A-Za-z \t])|(\w+:\/\/\S+)', ' ', news).split())
    stop_words = set(stopwords.words('english'))
    # tokens of words
    word_tokens = word_tokenize(x)
    for w in word_tokens:
        if w not in stop_words:
            filtered_sentence.append(w)

    output_news.append(" ".join(filtered_sentence))
    # print(analyze_sentiment(output_news[0]))
    return analyze_sentiment(output_news[0])


csvf = open('C:\\xampp\\htdocs\\SMP\\viewer\\sentiments\\sentiment.csv', 'w', newline="", encoding='latin-1')
csvff = csv.writer(csvf)
with open('C:\\xampp\\htdocs\\SMP\\viewer\\sentiments\\articles.csv', encoding='latin-1') as csv_file:
    csv_reader = csv.reader(csv_file)
    next(csv_file)
    for row in csv_reader:
       csvff.writerow([clean_news(row[4])])