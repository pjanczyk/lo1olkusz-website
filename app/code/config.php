<?php

return [
	'cron_log_path' => '/var/log/cron.log',
    'db_host' =>  'db',
    'db_port' => '3306',
    'db_database' => 'lo1olkusz',
    'db_user' => 'root',
    'db_password' => parse_ini_file('/usr/local/etc/.env')['DB_ROOT_PASSWORD'],
    'db_options' => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'Europe/Warsaw'"]
];