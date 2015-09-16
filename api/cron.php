<?php

use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Cron\CronTask;
use pjanczyk\lo1olkusz\Config;

$config = new Config();
$path = $config->getLogDir() . 'cron.log';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (file_exists($path)) {
        readfile($path);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    unlink($path);
    echo 'OK';
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = new CronTask;
    $task->run();
}
else {
    Json::badRequest();
}