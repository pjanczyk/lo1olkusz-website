<?php

return [
    'data_dir' => '/tmp',
    'log_dir' => '/var/log',
    'db_host' => getenv('DB_HOST'),
    'db_port' => getenv('DB_PORT'),
    'db_database' => getenv('DB_DATABASE'),
    'db_user' => getenv('DB_USER'),
    'db_password' => getenv('DB_PASSWORD'),
    'db_options' => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'Europe/Warsaw'"]
];