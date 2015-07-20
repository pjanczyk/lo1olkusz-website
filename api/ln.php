<?php

require_once 'src/Database.php';
require_once 'src/LuckyNumbersTable.php';
require_once 'src/Json.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\LuckyNumbersTable;
use pjanczyk\lo1olkusz\Json;

$model = new LuckyNumbersTable(Database::connect());

if (count($args) == 2) { # /api/ln/<date>
    $ln = $model->get($args[1]);
    if ($ln !== false) {
        Json::OK($ln);
    }
    else {
        Json::notFound();
    }
}
else {
    Json::badRequest();
}