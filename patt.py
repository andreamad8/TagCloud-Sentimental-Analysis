#!/usr/bin/python

import sys
from pattern.en import sentiment


try:
	sentences=[]
	sentences= sys.argv[1].split(',')
	ris=[]
	for i in range(0,len(sentences)):
		val= sentiment(sentences[i])
		print val[0]
	 
except IndexError:
	print []

