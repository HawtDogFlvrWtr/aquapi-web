# aquapi-web

# Crontabs
* * * * * /var/www/html/i2cParamPush.py
* * * * *  /var/www/html/api/triggerCron.sh
0 0 * * *  /usr/bin/php /var/www/html/api/cleanDB.php

# Install
apache2, php, mariadb-server, mariadb, git, phpmyadmin, python-smbus, i2c-tools

raspi-config enable i2c and reboot
mysql -u aquapi -p < aquapi.sql aquapi
