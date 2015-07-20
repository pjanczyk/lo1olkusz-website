<?php

require_once 'src/Database.php';
require_once 'src/ReplacementsTable.php';
require_once 'src/Json.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\ReplacementsTable;
use pjanczyk\lo1olkusz\Json;

$model = new ReplacementsTable(Database::connect());

if (count($args) == 3) { # /api/replacements/<date>/<class>
    $replacements = $model->get($args[2], $args[1]);
    if ($replacements !== false) {
        Json::OK($replacements);
    }
    else {
        Json::notFound();
    }
}
else {
    Json::badRequest();
}