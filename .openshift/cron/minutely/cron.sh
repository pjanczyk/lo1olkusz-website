#!/bin/bash
date >> ${OPENSHIFT_PHP_LOG_DIR}/cron.log
cd ${OPENSHIFT_REPO_DIR}
php ${OPENSHIFT_REPO_DIR}/cron.php