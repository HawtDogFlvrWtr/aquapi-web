#!/bin/sh
if ps -ef | grep -v grep | grep checkTriggers.php ; then
	echo "Trigger already running"
else
	echo "Running checktriggers"
	/usr/bin/php /var/www/html/api/checkTriggers.php >> /dev/null &
fi

if ps -ef | grep -v grep | grep i2cParamPush.py ; then
	echo "i2c Already running"
else
	echo "Running i2c"
	/var/www/html/i2cParamPush.py >> /dev/null &
fi
