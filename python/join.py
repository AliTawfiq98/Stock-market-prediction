import json
import pandas as pd
import sys
from nltk import word_tokenize, re
from nltk.corpus import stopwords

import textblob as t

from datetime import date
import dateutil.relativedelta

import requests
from HistoricalTest import Historical

import mysql.connector

test = 'FB'
ticker = sys.argv[1]
stock = Historical(ticker)

daysNo = stock.max_date - stock.min_date
years = daysNo.days / 365


stock.daily_seasonality = True
stock.weekly_seasonality = True
stock.yearly_seasonality = True
stock.training_years = round(years)
#

today = date.today()
prev_month = today + dateutil.relativedelta.relativedelta(months=-1)


key = '{} stock'.format(ticker)

url = ('http://newsapi.org/v2/everything?'
       'q={}&'
       'from={}'
       'sortBy=popularity&'
       'apiKey=b7de039e294740bb84d8dff8c2bbf97d').format(key, today)

response = requests.get(url)
response_json_string = json.dumps(response.json())
response_dict = json.loads(response_json_string)
articles_list = response_dict['articles']
df = pd.read_json(json.dumps(articles_list))
df = df['title']
df.str.replace('"', '')


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

    return analyze_sentiment(output_news[0])


arr = []
for row in df:
    arr.append(clean_news(row))

positive = 0
negative = 0
neutral = 0

for row in arr:
    if row == 'positive':
        positive = positive + 1
    elif row == 'negative':
        negative = negative + 1
    else:
        neutral = neutral + 1

total = positive + negative + neutral

positiveNewsPercentage = (positive / total) * 100
negativeNewsPercentage = (negative / total) * 100
neutralNewsPercentage = (neutral / total) * 100

print(positiveNewsPercentage)
print(negativeNewsPercentage)
print(neutralNewsPercentage)

change = stock.recommend()
print(change)

decision = ''

if change > 0.0:
    if positiveNewsPercentage > 50.0:
        decision = 'Buy'
    elif neutralNewsPercentage >= negativeNewsPercentage:
        decision = 'Buy'
    elif positiveNewsPercentage >= negativeNewsPercentage:
        decision = 'Buy'
    else:
        decision = 'Hold'
elif change < 0.0:
    if negativeNewsPercentage > 50.0:
        decision = 'Sell'
    elif negativeNewsPercentage > neutralNewsPercentage:
        decision = 'Sell'
    elif negativeNewsPercentage > positiveNewsPercentage:
        decision = 'Sell'
    else:
        decision = 'Hold'
else:
    decision = 'Hold'


mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  passwd="",
  database="smp"
)

mycursor = mydb.cursor()
sql = "INSERT INTO decision (decision) VALUES ('{}')".format(decision)
mycursor.execute(sql)

mydb.commit()