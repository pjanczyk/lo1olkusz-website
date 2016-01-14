<?php

spl_autoload_register(function ($class) {

    $prefix = 'pjanczyk\\lo1olkusz\\';
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);

    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        require $file;
    }
});