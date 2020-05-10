from HistoricalTest import Historical
import sys


if __name__ == '__main__':

    ticker = sys.argv[1]
    stock = Historical(ticker)
    daysNo = stock.max_date - stock.min_date
    years = daysNo.days / 365


    stock.daily_seasonality = True
    stock.weekly_seasonality = True
    stock.yearly_seasonality = True
    stock.training_years = round(years)
    stock.forecast(days=7)