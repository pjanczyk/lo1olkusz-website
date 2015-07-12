#!/bin/bash
export TZ = "/usr/share/zoneinfo/Europe/Warsaw"
NOW = $(date +%F)
FILE = ${OPENSHIFT_LOG_DIR}/cron.log
echo "[$NOW]" >> $FILE

cd ${OPENSHIFT_REPO_DIR}
php ./cron.php >> $FILE