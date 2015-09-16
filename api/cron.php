<?php

use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Cron\CronTask;
use pjanczyk\lo1olkusz\Config;

$config = new Config();
$path = $config->getLogDir() . 'cron.log';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('Content-Type: text');
    if (file_exists($path)) {
        readfile($path);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    header('Content-Type: text');
    unlink($path);
    echo 'OK';
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: text');
    $task = new CronTask;
    $task->run();
}
else {
    Json::badRequest();
}