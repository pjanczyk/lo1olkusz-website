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

//Created on 2015-07-15

require_once 'classes/Config.php';
require_once 'classes/Database.php';
require_once 'classes/Data.php';

use pjanczyk\lo1olkusz\Data;

$data = new Data(pjanczyk\lo1olkusz\connectToDb());

$config = $data->getConfig();

?>

<h4>Timetable</h4>
Current: <a href="/api/timetable.json"><?= $config['timetable'] ?></a>

<form action="/timetable" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="timetable">Select timetable file to upload:</label>
        <input type="file" name="timetable" id="timetable">
        <p class="help-block">File must be in <em>lo1olkusz app timetable</em> format</p>
    </div>
    <button type="submit" class="btn btn-default">Upload</button>
</form>

<h4>Autoupdate APK</h4>
Current: <a href="/api/lo1olkusz.apk"><?= $config['version'] ?></a>
