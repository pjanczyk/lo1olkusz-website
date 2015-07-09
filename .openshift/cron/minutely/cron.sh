#!/bin/bash
date >> ${OPENSHIFT_PHP_LOG_DIR}/cron.log
php $OPENSHIFT_REPO_DIR/cron.php