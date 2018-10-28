#!/usr/bin/env python
from weather import Weather, Unit
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
oldWeather = "sun"
oldTime = ""
oldPumpStatus = '1'
needResume = ['random_clouds', 'rolling_clouds', 'lightning', 'full_thunderstorm', 'storm1', 'storm2', 'lunar', 'lunar_storm', 'sunrise_sunset']
nonResume = ['resume', 'daylight', 'moonlight', 'sun', 'moon', 'rising']
cloudyWeather = [28, 30]
minorStorm = [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 18, 40, 42, 46]
majorStorm = [0, 1, 2, 3, 4, 17, 35, 37, 38, 39, 41, 43, 45, 47]
baseurl = "https://query.yahooapis.com/v1/public/yql?"


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

def irSend(template, command):
  call(['irsend', 'SEND_ONCE', template, command])

def lightsOut(onTime, offTime):
    currentTime = now.time()
    print("Current Time: "+str(currentTime)+" OnTime: "+str(onTime)+" offTime: "+str(offTime))
    if currentTime > onTime and currentTime < offTime:
      return False
    else: 
      return True
    return None, False

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

def getWeather():
  currentWeather = "resume"
  return currentWeather
  yql_query = "select * from weather.forecast where woeid="+WOEID
  yql_url = baseurl + urllib.urlencode({'q':yql_query}) + "&format=json"
  print yql_url
  result = urllib2.urlopen(yql_url).read()
  data = json.loads(result)
  code = int(data['query']['results']['channel']['item']['condition']['code'])
  on_time = dtTime(int(onTime[0]), int(onTime[1]))
  off_time = dtTime(int(offTime[0]), int(offTime[1]))
  print("is it currently night? "+str(lightsOut(on_time, off_time)))
  if not lightsOut(on_time, off_time):
    if code in minorStorm and not lightsOut(on_time, off_time):
      currentWeather = "lightning"
    elif code in majorStorm and not lightsOut(on_time, off_time):
      currentWeather = "full_thunderstorm"
    # Handle Cloudy
    elif cloudy == "yes" and code in cloudyWeather and not lightsOut(on_time, off_time):
      currentWeather = "random_clouds"
    else:
      currentWeather = "resume"
  else:
    currentWeather = "resume"
  currentWeather = "resume"
  return currentWeather


# Reseting light before continuing. 
print("Please make sure the light is turned on and all pumps are turned on")
time.sleep(2)
irSend('LOOP', 'resume')
time.sleep(2)
while True:
  try:
    now = datetime.now()
    onlineConfig = getConfig()
    onTime = onlineConfig['on_time'].split(":")
    offTime = onlineConfig['off_time'].split(":")
    cloudy = onlineConfig['make_cloudy'].lower()
    timer = int(onlineConfig['python_update']) * 60
    WOEID = onlineConfig['woeid']
    fileName = open(onlineConfig['log_file'], "a")
    performAction = onlineConfig['performAction']
    pumpStatus = onlineConfig['pumpStatus']
    lightStatus = onlineConfig['lightStatus']
    override = onlineConfig['override']
    tempScale = onlineConfig['tempScale']
    # Check if light should be off
    if lightStatus == "1" and oldWeather is not "light_pwr":
      if override == "resume":
        # Check if it's time to do the weather
        if oldTime == "" or time.time() >= oldTime + timer or (override == "resume" and oldWeather != "resume"):
          print("oldTime: "+str(oldTime)+" currentTime: "+str(time.time())+" override: "+override+" oldWeather: "+oldWeather)
          print("Checking temperature and weather again")
          # Get temperature and send to api
          temperature = read_temp()
          sendTemperature = urllib2.urlopen("http://www.mytankstats.com/api/pushValue.php?metric=Temperature&value="+str(temperature[1])).read()
          currentWeather = getWeather()
          if currentWeather != oldWeather: # No need to change if this is the current weather
            if oldWeather in needResume and currentWeather in nonResume:
              irSend('LOOP', 'resume')
            newText = "Changing weather to "+currentWeather
            irSend('LOOP', currentWeather)
          else:
            newText = "Weather has not changed."
          oldTime = time.time()
        else:
          newText = "Not time to recheck weather"
        
      elif override != "resume" and override != oldWeather:
        if oldWeather in needResume and override in nonResume:
          newText = "Weather override ("+override+") requires resuming"
          irSend('LOOP', 'resume')
          irSend('LOOP', override)
        else:
          irSend('LOOP', override)
        currentWeather = override
      elif override != "resume" and override == oldWeather:
        newText = "Weather override ("+override+") is still in effect"
      #print("old: "+oldWeather+" new: "+currentWeather)
      oldWeather = currentWeather
      sendCurrentWeather = urllib2.urlopen("http://www.mytankstats.com/api/pushValue.php?status="+currentWeather).read()
      print(now.strftime("%Y-%m-%d %H:%M")+": Current weather is "+currentWeather+". "+newText)
      fileName.write(now.strftime("%Y-%m-%d %H:%M")+": Current weather is "+currentWeather+". "+newText+"\n")
      fileName.close()
    elif lightStatus == "0" and oldWeather is not "light_pwr":
      irSend('LOOP', 'light_pwr')
      oldWeather = 'light_pwr'
      print("Powering light off")
    elif lightStatus == "1" and oldWeather is "light_pwr":
      irSend('LOOP', 'light_pwr')
      time.sleep(1)
      irSend('LOOP', 'resume')
      oldWeather = 'resume'
      print("Powering light back on")
    else:
      print("Light is marked as off and already off")
    if oldPumpStatus != pumpStatus:
      irSend('LOOP', 'pump_pwr')
      oldPumpStatus = pumpStatus
      if pumpStatus == '1':
        print("Pump was turned on")
      else:
        print("Pump was turned off")
    time.sleep(1)
  except:
    print(str(sys.exc_info())+"\n")
