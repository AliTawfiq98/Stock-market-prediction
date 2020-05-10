import pandas as pd
import numpy as np
import fbprophet
import datetime as dt
import urllib.request
import json
import matplotlib.pyplot as plt
import matplotlib


class Historical():

    # Initialization requires a ticker symbol
    def __init__(self, ticker):
        # , exchange='WIKI'
        # Enforce capitalization
        ticker = ticker.upper()

        # Symbol is used for labeling plots
        self.symbol = ticker

        # Use Personal Api Key
        api_key = 'DEO9Z5UIODH7Y6BY'
        # quandl.ApiConfig.api_key = 'qz9oPMZw3m_TJQ9pjKpy'

        # Retrieval the financial data

        # stock = quandl.get('%s/%s' % (exchange, ticker))
        # Retrieval the financial data
        try:
            url_string = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=%s&outputsize=full&apikey=%s"%(ticker,api_key)
            with urllib.request.urlopen(url_string) as url:

                data = json.loads(url.read().decode())
                # extract stock market data

                data = data['Time Series (Daily)']

                stock = pd.DataFrame(columns=['Date', 'Open', 'High', 'Low', 'Close'])
                for k, v in data.items():
                    date = dt.datetime.strptime(k, '%Y-%m-%d')
                    data_row = [date.date(), float(v['1. open']), float(v['2. high']),
                                float(v['3. low']), float(v['4. close'])]
                    stock.loc[-1, :] = data_row
                    stock.index = stock.index + 1
                stock.set_index('Date', inplace=True)


        except Exception as e:
            print('Error Retrieving Data.')
            print(e)
            return

        # Set the index to a column called Date
        stock = stock.reset_index(level=0)
        # print(stock.head())
        # Columns required for prophet
        stock['ds'] = stock['Date']

        if ('Adj. Close' not in stock.columns):
            stock['Adj. Close'] = stock['Close']
            stock['Adj. Open'] = stock['Open']

        stock['y'] = stock['Adj. Close']
        stock['Daily Change'] = stock['Adj. Close'] - stock['Adj. Open']

        # Data assigned as class attribute
        self.stock = stock.copy()

        # Minimum and maximum date in range
        self.min_date = min(stock['Date'])
        self.max_date = max(stock['Date'])

        # Find max and min prices and dates on which they occurred
        self.max_price = np.max(self.stock['y'])
        self.min_price = np.min(self.stock['y'])

        self.min_price_date = self.stock[self.stock['y'] == self.min_price]['Date']
        self.min_price_date = self.min_price_date[self.min_price_date.index[0]]
        self.max_price_date = self.stock[self.stock['y'] == self.max_price]['Date']
        self.max_price_date = self.max_price_date[self.max_price_date.index[0]]

        # The starting price (starting with the opening price)
        self.starting_price = float(self.stock.loc[0, 'Adj. Open'])

        # The most recent price
        self.most_recent_price = float(self.stock.loc[self.stock.index[-1], 'y'])

        # Whether or not to round dates
        self.round_dates = True

        # Number of years of data to train on
        self.training_years = 20

        # Prophet parameters
        # Default prior from library
        self.changepoint_prior_scale = 0.05
        self.weekly_seasonality = False
        self.daily_seasonality = False
        self.monthly_seasonality = False
        self.yearly_seasonality = False
        self.changepoints = None

        print('{} Historical Data Initialized. Data covers {} to {}.'.format(self.symbol,
                                                                     self.min_date,
                                                                     self.max_date))

    def change_dates(self, start_date, end_date):

        # Default start and end date are the beginning and end of data
        if start_date is None:
            start_date = self.min_date
        if end_date is None:
            end_date = self.max_date

        try:
            # Convert to pandas datetime for indexing dataframe
            start_date = pd.to_datetime(start_date)
            end_date = pd.to_datetime(end_date)

        except Exception as e:
            print('Enter valid pandas date format.')
            print(e)
            return

        valid_start = False
        valid_end = False

        # User will continue to enter dates until valid dates are met
        while (not valid_start) & (not valid_end):
            valid_end = True
            valid_start = True

            if end_date < start_date:
                print('End Date must be later than start date.')
                start_date = pd.to_datetime(input('Enter a new start date: '))
                end_date = pd.to_datetime(input('Enter a new end date: '))
                valid_end = False
                valid_start = False

            else:
                if end_date > self.max_date:
                    print('End Date exceeds data range')
                    end_date = pd.to_datetime(input('Enter a new end date: '))
                    valid_end = False

                if start_date < self.min_date:
                    print('Start Date is before date range')
                    start_date = pd.to_datetime(input('Enter a new start date: '))
                    valid_start = False

        return start_date, end_date

    def new_ds(self, start_date, end_date, df=None):

        # Default is to use the object stock data
        if not df:
            df = self.stock.copy()

        start_date, end_date = self.change_dates(start_date, end_date)

        # keep track of whether the start and end dates are in the data
        start_in = True
        end_in = True

        # If user wants to round dates (default behavior)
        if self.round_dates:
            # Record if start and end date are in df
            if (start_date not in list(df['Date'])):
                start_in = False
            if (end_date not in list(df['Date'])):
                end_in = False

            # If both are not in dataframe, round both
            if (not end_in) & (not start_in):
                trim_df = df[(df['Date'] >= start_date) &
                             (df['Date'] <= end_date)]

            else:
                # If both are in dataframe, round neither
                if (end_in) & (start_in):
                    trim_df = df[(df['Date'] >= start_date) &
                                 (df['Date'] <= end_date)]
                else:
                    # If only start is missing, round start
                    if (not start_in):
                        trim_df = df[(df['Date'] > start_date) &
                                     (df['Date'] <= end_date)]
                    # If only end is imssing round end
                    elif (not end_in):
                        trim_df = df[(df['Date'] >= start_date) &
                                     (df['Date'] < end_date)]


        else:
            valid_start = False
            valid_end = False
            while (not valid_start) & (not valid_end):
                start_date, end_date = self.change_dates(start_date, end_date)

                # No round dates, if either data not in, print message and return
                if (start_date in list(df['Date'])):
                    valid_start = True
                if (end_date in list(df['Date'])):
                    valid_end = True

                # Check to make sure dates are in the data
                if (start_date not in list(df['Date'])):
                    print('Start Date not in data (either out of range or not a trading day.)')
                    start_date = pd.to_datetime(input(prompt='Enter a new start date: '))

                elif (end_date not in list(df['Date'])):
                    print('End Date not in data (either out of range or not a trading day.)')
                    end_date = pd.to_datetime(input(prompt='Enter a new end date: '))

            # Dates are not rounded
            trim_df = df[(df['Date'] >= start_date) &
                         (df['Date'] <= end_date.date)]

        return trim_df

    def stock_analysis(self, start_date=None, end_date=None, stats=['Adj. Close'], plot_type='basic'):

        self.restore()

        if start_date is None:
            start_date = self.min_date
        if end_date is None:
            end_date = self.max_date

        stock_plot = self.new_ds(start_date, end_date)

        colors = ['r', 'b', 'g', 'y', 'c', 'm']

        for i, stat in enumerate(stats):

            stat_min = min(stock_plot[stat])
            stat_max = max(stock_plot[stat])

            stat_avg = np.mean(stock_plot[stat])

            date_stat_min = stock_plot[stock_plot[stat] == stat_min]['Date']
            date_stat_min = date_stat_min[date_stat_min.index[0]]
            date_stat_max = stock_plot[stock_plot[stat] == stat_max]['Date']
            date_stat_max = date_stat_max[date_stat_max.index[0]]

            print('Maximum {} = {:.2f} on {}.'.format(stat, stat_max, date_stat_max))
            print('Minimum {} = {:.2f} on {}.'.format(stat, stat_min, date_stat_min))
            print(
                'Current {} = {:.2f} on {}.\n'.format(stat, self.stock.loc[self.stock.index[-1], stat], self.max_date))

            # Percentage y-axis
            if plot_type == 'pct':
                # Simple Plot
                plt.style.use('fivethirtyeight');
                if stat == 'Daily Change':
                    plt.plot(stock_plot['Date'], 100 * stock_plot[stat],
                             color=colors[i], linewidth=2.4, alpha=0.9,
                             label=stat)
                else:
                    plt.plot(stock_plot['Date'], 100 * (stock_plot[stat] - stat_avg) / stat_avg,
                             color=colors[i], linewidth=2.4, alpha=0.9,
                             label=stat)

                plt.xlabel('Date');
                plt.ylabel('Change Relative to Average (%)');
                plt.title('%s Stock History' % self.symbol);
                plt.legend(prop={'size': 10})
                plt.grid(color='k', alpha=0.4);

                # Stat y-axis
            elif plot_type == 'basic':
                plt.style.use('fivethirtyeight');
                plt.plot(stock_plot['Date'], stock_plot[stat], color=colors[i], linewidth=3, label=stat, alpha=0.8)
                plt.xlabel('Date');
                plt.ylabel('US $');
                plt.title('%s Stock History' % self.symbol);
                plt.legend(prop={'size': 10})
                plt.grid(color='k', alpha=0.4);

        plt.show();

    @staticmethod
    def restore():

        matplotlib.rcdefaults()


        matplotlib.rcParams['figure.figsize'] = (8, 5)
        matplotlib.rcParams['axes.labelsize'] = 10
        matplotlib.rcParams['xtick.labelsize'] = 8
        matplotlib.rcParams['ytick.labelsize'] = 8
        matplotlib.rcParams['axes.titlesize'] = 14
        matplotlib.rcParams['text.color'] = 'k'

    def adjust_weekends(self, dataframe):

        dataframe = dataframe.reset_index(drop=True)

        weekends = []

        for i, date in enumerate(dataframe['ds']):
            if (date.weekday() == 5):
                weekends.append(i)
            if (date.weekday() == 6):
                weekends.append(i)
        dataframe = dataframe.drop(weekends, axis=0)

        return dataframe

    def create_model(self):

        # Make the model
        model = fbprophet.Prophet(daily_seasonality=self.daily_seasonality,
                                  weekly_seasonality=self.weekly_seasonality,
                                  yearly_seasonality=self.yearly_seasonality,
                                  changepoint_prior_scale=self.changepoint_prior_scale,
                                  changepoints=self.changepoints)

        if self.monthly_seasonality:
            # Add monthly seasonality
            model.add_seasonality(name='monthly', period=30.5, fourier_order=5)
        return model

    def forecast(self, days):
        train = self.stock[self.stock['Date'] > (max(self.stock['Date']) - pd.DateOffset(years=self.training_years))]

        model = self.create_model()

        model.fit(train)

        # Future dataframe with specified number of days to predict
        future = model.make_future_dataframe(periods=days, freq='D')
        future = model.predict(future)
        self.stock['Date'] = pd.to_datetime(self.stock['Date'])


        future = future[future['ds'] >= max(self.stock['Date'])]

        future = self.adjust_weekends(future)
        future['diff'] = future['yhat'].diff()

        future = future.dropna()

        future['direction'] = (future['diff'] > 0) * 1

        future = future.rename(columns={'ds': 'Date', 'yhat': 'estimate', 'diff': 'change',
                                        'yhat_upper': 'upper', 'yhat_lower': 'lower'})

        future_increase = future[future['direction'] == 1]
        future_decrease = future[future['direction'] == 0]

        print('\nPredicted Increase: \n')
        print(future_increase[['Date', 'estimate', 'change', 'upper', 'lower']])

        print('\nPredicted Decrease: \n')
        print(future_decrease[['Date', 'estimate', 'change', 'upper', 'lower']])

        self.restore()

        plt.style.use('fivethirtyeight')
        matplotlib.rcParams['axes.labelsize'] = 10
        matplotlib.rcParams['xtick.labelsize'] = 8
        matplotlib.rcParams['ytick.labelsize'] = 8
        matplotlib.rcParams['axes.titlesize'] = 12

        fig, ax = plt.subplots(1, 1, figsize=(8, 6))

        ax.plot(future_increase['Date'], future_increase['estimate'], 'g^', ms=12, label='Stock Price Increase')
        ax.plot(future_decrease['Date'], future_decrease['estimate'], 'rv', ms=12, label='Stock Price Decrease')

        ax.errorbar(future['Date'].dt.to_pydatetime(), future['estimate'],
                    yerr=future['upper'] - future['lower'],
                    capthick=1.4, color='k', linewidth=2,
                    ecolor='darkblue', capsize=4, elinewidth=1)

        plt.legend(loc=2, prop={'size': 10})
        plt.xticks(rotation='45')
        plt.xlabel('Date')
        plt.gca().axes.get_yaxis().set_visible(False)

        # plt.show()

        plt.savefig('C:\\xampp\\htdocs\\SMP\\viewer\\predictions\\%s.png'%(self.symbol), bbox_inches='tight', pad_inches=0)
