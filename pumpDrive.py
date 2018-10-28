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
import random

os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')

configFile = '/etc/weatherSync/weather.conf'
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + '28*')[0]
device_file = device_folder + '/w1_slave'
oldTime = ""
count = 0

def irSend(template, command):
  call(['irsend', 'SEND_ONCE', template, command])

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

def resetPump():
  # Reset pump to ensure it's on 03
  print("Resetting pump to lowest cycle before starting... please wait")
  for i in range(5):
    irSend('LOOP', 'frequency_up')
    time.sleep(0.5)

resetPump()

# Reseting light before continuing. 
while True:
  if count >= 60:
    resetPump()
    count = 0
  irSend('LOOP', 'frequency_down')
  time.sleep(0.7)
  irSend('LOOP', 'frequency_up')
  count += 1
