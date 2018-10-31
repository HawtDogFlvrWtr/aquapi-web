#!/bin/sh
if ps -ef | grep -v grep | grep checkTriggers.php ; then
	exit 0
else
	/usr/bin/php /var/www/html/api/checkTriggers.php >> /dev/null &
	exit 0
fi
