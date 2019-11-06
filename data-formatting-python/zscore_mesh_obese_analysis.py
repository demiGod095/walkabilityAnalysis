import pandas as pd
import couchdb
import json  
from pandas.io.json import json_normalize  

couch = couchdb.Server('http://admin_test_walk:admin_test_walk@115.146.93.173:5984/')
print('connected')
# del couch['testgreatermelb']
# print('db deleted')
# db = couch.create('testgreatermelb')
print('db created')
# db = couch['testwalk2']
# print(db)

#############################################################################################
# For ZScore
with open('zScore-greaterMelb_noGeo.json') as f:
    nested = json.load(f)
data = pd.DataFrame.from_dict(json_normalize(nested))
data = data['features']
zscore = pd.DataFrame.from_records(data[0])
zscore = zscore['properties']
zscore1 = pd.DataFrame.from_records(zscore)
zscore1 = zscore1[['SA2_MAIN16','SA2_NAME16','SumZScore']]
zscore2 = zscore1.groupby(['SA2_MAIN16','SA2_NAME16'],as_index=False)['SumZScore'].mean()
zscore2['SA2_MAIN16'] = zscore2['SA2_MAIN16'].astype(str).astype(int)

# For mesh 
with open('mesh_greaterMelb_conv.geojson') as f:
    nested = json.load(f)
data = pd.DataFrame.from_dict(json_normalize(nested))
data = data['features']
df = pd.DataFrame.from_records(data[0])
df = df['properties']
df1 = pd.DataFrame.from_records(df)
df1 = df1[['sa2_mainco','mb_categor','area_alber']]
park = df1.loc[df1['mb_categor'] == "Parkland"]
parkA = park.groupby(['sa2_mainco'],as_index=False)['area_alber'].agg('sum')
park = park[['sa2_mainco']]
park['Total_Parks'] = ""
park = park.groupby(['sa2_mainco'],as_index=False).count()
parkA = parkA[['area_alber']]
park_concat = pd.concat([park,parkA], axis=1)
park_concat = park_concat.rename(columns={"sa2_mainco":"SA2_MAIN16"})
park_concat = park_concat[['SA2_MAIN16','Total_Parks','area_alber']]
park_concat['SA2_MAIN16'] = park_concat['SA2_MAIN16'].astype(str).astype(int)

data1 = json.load(open('SA2-polygons-melb-greater_WGS84.geojson'))
data21 = pd.DataFrame(data1['features'])
data2 = data21['properties']
df3 = pd.DataFrame.from_records(data2)
df33 = df3['AREASQKM16']
df3 = df3['SA2_MAIN16']
dat = pd.concat([df3,df33], axis=1)
dat['SA2_MAIN16'] = dat['SA2_MAIN16'].astype(str).astype(int)
df_merge_col = pd.merge(dat,park_concat, on='SA2_MAIN16')
df_merge_col['Park_Density'] = (df_merge_col.area_alber * 100 / df_merge_col.AREASQKM16)
#print(df_merge_col)
park_zscore = pd.merge(left= zscore2, right = df_merge_col, how ='left', left_on='SA2_MAIN16', right_on='SA2_MAIN16')
park_zscore = park_zscore.fillna(0)

# OBESITY MERGE
with open('SA2_Health_Risk_Factors_-_Modelled_Estimate_2011-2013.json') as f:
    nested = json.load(f)
data = pd.DataFrame.from_dict(json_normalize(nested))
data = data['features']
obese = pd.DataFrame.from_records(data[0])
obese = obese['properties']
obese1 = pd.DataFrame.from_records(obese)
obese1 = obese1[['area_code','obese_p_me_1_no_3_11_7_13','obese_p_me_2_rate_3_11_7_13']]
obese1 = obese1.rename(columns={"area_code":"SA2_MAIN16"})
zscore_park_obese = pd.merge(park_zscore, obese1, on='SA2_MAIN16')
test1 = json.loads(zscore_park_obese.to_json(orient = "records", date_format = "epoch", double_precision = 10, force_ascii = True, date_unit = "ms", default_handler = None))
str1 = {'_id': 'obese_park_data','features': test1}
db.save(str1)

###################################################################################################
# For Restaurant analysis
with open('City_of_Melbourne_CLUE_Cafes__Restaurants_and_Bistros_Seats__Points__2017.json') as f:
    nested = json.load(f)
data = pd.DataFrame.from_dict(json_normalize(nested))
data = data['features']
df = pd.DataFrame.from_records(data[0])
df = df['properties']
df1 = pd.DataFrame.from_records(df)
df1 = df1[['clue_small_area','industry_anzsic4_description']]
rest = df1.loc[df1['industry_anzsic4_description'] == "Cafes and Restaurants"]
rest = rest[['clue_small_area']]
rest['clue_small_area'] = rest['clue_small_area'].replace(regex=True,to_replace=r'\([^)]*\)',value=r'')
rest['clue_small_area'] = rest['clue_small_area'].replace(to_replace ="Kensington", value ="Kensington (Vic.)")
rest['clue_small_area'] = rest['clue_small_area'].replace(to_replace ="South Yarra", value ="South Yarra - West")
rest['clue_small_area'] = rest['clue_small_area'].str.strip()
rest["Cafes and Restaurants"] = ""
rest = rest.rename(columns={"clue_small_area":"SA2_NAME16"})
rest = rest.groupby('SA2_NAME16').count()

with open('yarra-food-establishments.geojson') as f:
    nested1 = json.load(f)
data = pd.DataFrame.from_dict(json_normalize(nested1))
data = data['features']
df = pd.DataFrame.from_records(data[0])
df = df['properties']
df1 = pd.DataFrame.from_records(df)
df1 = df1[['suburb','industry_desc']]
rest1 = df1.loc[df1['industry_desc'] == "Cafes and Restaurants"]
rest1 = rest1[['suburb']]
rest1['suburb'] = rest1['suburb'].replace(to_replace ="Carlton North", value ="Carlton North - Princes Hill")
rest1['suburb'] = rest1['suburb'].replace(to_replace ="Princes Hill", value ="Carlton North - Princes Hill")
rest1['suburb'] = rest1['suburb'].replace(to_replace ="Richmond", value ="Richmond (Vic.)")
rest1['suburb'] = rest1['suburb'].str.strip()
rest1["Cafes and Restaurants"] = ""
rest1 = rest1.rename(columns={"suburb":"SA2_NAME16"})
rest1 = rest1.groupby('SA2_NAME16').count()

# Merging melb and yarra
rest_concat = pd.concat([rest,rest1], axis=0)

all_rest_merge = pd.merge(zscore_park_obese, rest_concat, on='SA2_NAME16')
print (all_rest_merge)

test2 = json.loads(all_rest_merge.to_json(orient = "records", date_format = "epoch", double_precision = 10, force_ascii = True, date_unit = "ms", default_handler = None))
str1 = {'_id': 'rest_all_data','features': test2}
db.save(str1)
