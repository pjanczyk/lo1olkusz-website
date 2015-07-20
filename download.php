<?php

require_once 'src/Config.php';

use pjanczyk\lo1olkusz\Config;

$path = Config::getDataDir() . 'apk';

if (file_exists($path)) {
    header('Content-Type: application/vnd.android.package-archive');
    header('Content-Disposition: attachment; filename="lo1olkusz-app.apk"');
    readfile(Config::getDataDir() . 'apk');
}
else {
    header('HTTP/1.0 404 Not Found');
    readfile('html/404.html');
}