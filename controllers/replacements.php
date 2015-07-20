<?php
/** @var array $args */

require_once 'classes/Database.php';
require_once 'classes/ReplacementsTable.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\ReplacementsTable;

date_default_timezone_set('Europe/Warsaw');

$model = new ReplacementsTable(Database::connect());

$lns = $model->getAll([ReplacementsTable::FIELD_DATE, ReplacementsTable::FIELD_CLASS, ReplacementsTable::FIELD_LAST_MODIFIED]);

include 'views/ln_list.php';