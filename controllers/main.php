<?php
/**
 * Copyright 2015 Piotr Janczyk
 *
 * This file is part of I LO Olkusz Unofficial App.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//Created on 2015-07-13

require_once 'classes/Config.php';
require_once 'classes/Database.php';
require_once 'classes/Status.php';

use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Status;

date_default_timezone_set('Europe/Warsaw');
$statusPath = Config::getDataDir() . 'status';

if (isset($_POST['update-status'])) {
    $db = new Database;
    Status::update($db);
}

$statusTimestamp = file_exists($statusPath) ? date('Y-m-d H:i:s', filemtime($statusPath)) : "not exist";
?>


<?php include 'html/header.php' ?>

<h4>Status file</h4>
<a href="/api/status.json"><?=$statusTimestamp?></a>

<?php include 'html/footer.php' ?>