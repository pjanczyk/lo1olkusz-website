#!/bin/sh
echo "[$(date +"%F %T %Z")]"
cd /var/www/html
/usr/local/bin/php run_cron