#To find mean zscore for sa4 level

import pandas as pd
import json  
from pandas.io.json import json_normalize  

with open('zScore-greaterMelb_noGeo.json') as f:
    nested = json.load(f)

data = pd.DataFrame.from_dict(json_normalize(nested))

data = data['features']


df = pd.DataFrame.from_records(data[0])
df = df['properties']
df1 = pd.DataFrame.from_records(df)

df1 = df1[['SA4_CODE16','SA4_NAME16','SumZScore']]

df2 = df1.groupby(['SA4_CODE16','SA4_NAME16'],as_index=False)['SumZScore'].mean()

#print(df2)

df2.to_json(r'SA4ZScore.json', orient = "records", date_format = "epoch", double_precision = 10, force_ascii = True, date_unit = "ms", default_handler = None)

