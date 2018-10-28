#!/usr/bin/env python
from subprocess import call
from datetime import datetime, time as dtTime
import time
import sys
import os
import glob
import configparser
import urllib
import urllib2
import json

os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')

configFile = '/etc/weatherSync/weather.conf'
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + '28*')[0]
device_file = device_folder + '/w1_slave'
oldTime = ""

def read_temp_raw():
  f = open(device_file, 'r')
  lines = f.readlines()
  f.close()
  return lines

def read_temp():
  lines = read_temp_raw()
  while lines[0].strip()[-3:] != 'YES':
    time.sleep(0.2)
    lines = read_temp_raw()
  equals_pos = lines[1].find('t=')
  if equals_pos != -1:
    temp_string = lines[1][equals_pos+2:]
    temp_c = float(temp_string) / 1000.0
    temp_f = temp_c * 9.0 / 5.0 + 32.0
  return temp_c, temp_f

def getConfig():
  # Config Settings
  config = configparser.ConfigParser()
  config.sections()
  # Get configuration information
  config.read(configFile)
  apiKey = config['DEFAULT']['apiKey']
  url = config['DEFAULT']['onlineURL']
  onlineConfig = json.loads(urllib2.urlopen(url+"?apikey="+apiKey).read())
  return onlineConfig

onlineConfig = getConfig()
tempScale = onlineConfig['tempScale']

# Get temperature and send to api
try:
    temperature = read_temp()
except:
    temperature = [0,0]

print(temperature)
sendTemperature = urllib2.urlopen("http://localhost/api/pushValue.php?metric=Temperature&value="+str(temperature[0])).read()
