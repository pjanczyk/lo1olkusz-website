<?php
/** @var array $args */

require_once 'classes/Database.php';
require_once 'classes/LuckyNumbersTable.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\LuckyNumbersTable;

date_default_timezone_set('Europe/Warsaw');

$model = new LuckyNumbersTable(Database::connect());

$lns = $model->getAll([LuckyNumbersTable::FIELD_DATE, LuckyNumbersTable::FIELD_LAST_MODIFIED]);

include 'views/ln_list.php';