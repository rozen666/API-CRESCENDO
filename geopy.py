#! /usr/bin/env python
#import sys
from geopy.distance import geodesic


def getDistance(zip1, zip2):
	#return ((geodesic(zip1, zip2).km)*1.4)
	return (geodesic(zip1, zip2).kilometers)*1.4
	

#x1 = sys.argv[1]
#x2 = sys.argv[2]
#y1 = sys.argv[3]
#y2 = sys.argv[4]

#zip1 = (x1,x2)
#zip2 = (y1,y2)
zip1 = ('19.4853', '-99.1821')
zip2 = ('25.6449', '-100.311')

print(getDistance(zip1, zip2))
# jsonData= {
# 	'NoRows' :1,
# 	"Status": True,
# 	"Data": {
# 		'distance' :  distance
# 	},
# }
# print (jsonData)