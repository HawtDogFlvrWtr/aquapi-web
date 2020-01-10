#!/bin/sh
if ps -ef | grep -v grep | grep i2cParamPush.py ; then
	exit 0
else
	echo "Running i2c"
	/var/www/html/i2cParamPush.py >> /dev/null &
	exit 0
fi

