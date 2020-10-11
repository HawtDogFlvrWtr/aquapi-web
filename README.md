# Aquapi-Web
Install

- Enable i2c in raspi-config and reboot
- Rename system to aquapi-controller in raspi-config and reboot
- Install apache2, php, mariadb-server, phpmyadmin, php-redis, redis
- Import sql file into database

# CRONJOB SETTINGS
* * * * * /opt/aquapi-web/api/triggerCron.sh
* * * * * php /var/www/html/api/chartData.php 1-hour
* * * * * php /var/www/html/api/chartData.php 3-hour
* * * * * php /var/www/html/api/chartData.php 6-hour
* * * * * php /var/www/html/api/chartData.php 12-hour
*/5 * * * * php /var/www/html/chartData.php 1-day
*/15 * * * * php /var/www/html/api/chartData.php 2-day
*/20 * * * * php /var/www/html/api/chartData.php 1-week
#10 * * * * php /var/www/html/api/chartData.php 1-month
#0 * * * * php /var/www/html/api/chartData.php 3-month
