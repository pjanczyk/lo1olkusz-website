<?php

return [
    'data_dir' => $_ENV['OPENSHIFT_DATA_DIR'],
    'log_dir' => $_ENV['OPENSHIFT_PHP_LOG_DIR'],
    'db_host' => $_ENV['OPENSHIFT_MYSQL_DB_HOST'],
    'db_port' => $_ENV['OPENSHIFT_MYSQL_DB_PORT'],
    'db_database' => 'lo1olkusz',
    'db_user' => $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'],
    'db_password' => $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'],
    'db_options' => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'Europe/Warsaw'"]
];