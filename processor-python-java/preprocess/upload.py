import couchdb
import json
from tqdm import tqdm

def web_upload(fileName):
	global couch
	try:
		db = couch.create(fileName)
	except Exception as e:
		print('database error:',fileName, e)
	
	db = couch[fileName]
	
	try:
		with open('/home/ec2-user/pre/web/'+fileName+'.json') as f:
			data = json.load(f)
			db.save({'_id': 'data', 'data': data})
	except Exception as e:
		print('doc error:',fileName, e)
	

def feature_upload(fileName, idName):
	global couch
	try:
		db = couch.create(fileName)
	except Exception as e:
		print('database error:',fileName, e)
	
	db = couch[fileName]

	with open('/home/ec2-user/pre/features/'+fileName+'.geojson') as f:
		nested = json.load(f)

		meta = {'_id': 'meta', 'name': fileName,'type': nested['type'], 'bbox': nested['bbox'], 'crs':nested['crs'] }
		
		try:
			db.save(meta)
		except Exception as e:
			print('meta error:',fileName, e)
		

		print('inserting:',fileName)
		for feature in tqdm(nested['features']):
			element = {'_id' : feature['properties'][idName], 'feature': feature}
			try:
				db.save(element)
			except Exception as e:
				print('doc error:', feature['properties'][idName], e)
			

with open('/home/ec2-user/couchdb.txt','r') as db:
	content = f.read().split()
	host = content[0]
	user = content[1]
	passw = content[2]
	port = 5984

url = 'http://'+user+':'+passw+'@'+host+':'+str(port)+'/'

couch = couchdb.Server(url)

web_files = [
	'web-sa2-zscore',
	'web-sa3-zscore',
	'web-sa4-zscore',
	'web-zresult-obese-park',
	'web-zresult-rest',
	'web-result-obese'
	]

feature_files_ids = [
	{'nm': 'sa1-polygons_melb-greater', 'id': 'SA1_MAIN16'},
	{'nm': 'sa2-polygons_melb-greater', 'id': 'SA2_MAIN16'},
	{'nm': 'sa3-polygons_melb-greater', 'id': 'SA3_CODE16'},
	{'nm': 'sa4-polygons_melb-greater', 'id': 'SA4_CODE16'},
	{'nm': 'sa1-points_aus_centroids', 'id': 'SA1_MAIN16'},
	{'nm': 'sa2-nogeo_aus_obesity_2013', 'id': 'area_code'},
	{'nm': 'sa1-zscore-poly_melb-greater', 'id': 'SA1_MAIN16'},
	{'nm': 'psma-lines_melb-greater_UTM', 'id': 'st_lne_pid'}
	]

for file in web_files:
	web_upload(file)

for file in feature_files_ids:
	feature_upload(file['nm'], file['id'])