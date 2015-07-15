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
use pjanczyk\lo1olkusz\Config;

$data = new Data(pjanczyk\lo1olkusz\connectToDb());

$config = $data->getConfig();

$alerts = [];

if (isset($_FILES['timetable-file'])
    && $_FILES['timetable-file']['error'] == UPLOAD_ERR_OK) {

    $tmpName = $_FILES['timetable-file']["tmp_name"];
    $name = Config::getDataDir() . 'timetable';
    if (move_uploaded_file($tmpName, $name)) {
        $alerts[] = 'Changed timetable file';
    }
}
if (isset($_POST['timetable-version'])) {
    if ($data->setConfigValue('timetable', $_POST['timetable-version'])) {
        $alerts[] = 'Changed timetable version';
    }
}

if (isset($_FILES['apk-file'])
    && $_FILES['apk-file']['error'] == UPLOAD_ERR_OK) {

    $tmpName = $_FILES['apk-file']["tmp_name"];
    $name = Config::getDataDir() . 'apk';
    if (move_uploaded_file($tmpName, $name)) {
        $alerts[] = 'Changed APK file';
    }
}
if (isset($_POST['apk-version'])) {
    if ($data->setConfigValue('version', $_POST['apk-version'])) {
        $alerts[] = 'Changed APK version';
    }
}

?>

<?php if ($alerts): ?>
<div class="alert alert-success" role="alert">
    <?= implode('<br/>', $alerts) ?>
</div>
<?php endif ?>

<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Timetable</h3>
            </div>
            <div class="panel-body">
                <?php if (isset($config['timetable'])): ?>
                Current: <a href="/api/timetable.json"><?= $config['timetable'] ?></a><br/>
                <?php endif ?>

                <a class="show-next btn btn-default">Change</a>

                <form action="/settings" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="timetable-version">Version</label>
                        <input type="text" class="form-control" name="timetable-version" id="timetable-version" placeholder="Version">
                    </div>
                    <div class="form-group">
                        <label for="timetable-file">File:</label>
                        <input type="file" name="timetable-file" id="timetable-file">
                    </div>
                    <button type="submit" class="btn btn-default">Change</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Autoupdate APK</h3>
            </div>
            <div class="panel-body">
                <?php if (isset($config['version'])): ?>
                Current: <a href="/api/lo1olkusz.apk"><?= $config['version'] ?></a><br/>
                <?php endif ?>

                <a class="show-next btn btn-default">Change</a>

                <form action="/settings" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="apk-version">Version</label>
                        <input type="text" class="form-control" name="apk-version" id="apk-version" placeholder="Version">
                    </div>
                    <div class="form-group">
                        <label for="apk-file">APK file:</label>
                        <input type="file" name="apk-file" id="apk-file">
                    </div>
                    <button type="submit" class="btn btn-default">Change</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(".show-next").each(function() {
    $(this).next().hide();
    $(this).click(function() {
        $(this).hide();
        $(this).next().slideDown();
    });
});
</script>

