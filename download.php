<?php

require 'autoloader.php';

use pjanczyk\lo1olkusz\Config;

$config = new Config;

$path = $config->getDataDir() . 'apk';

if (file_exists($path)) {
    header('Content-Type: application/vnd.android.package-archive');
    header('Content-Disposition: attachment; filename="lo1olkusz-app.apk"');
    readfile($path);
}
else {
    header('HTTP/1.0 404 Not Found');
    readfile('html/404.html');
}