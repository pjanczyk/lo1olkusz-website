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
?>

<?php include 'templates/dashboard/header.php' ?>

<div class="page-header">
    <h1>Ustawienia</h1>
</div>

<?php include 'templates/dashboard/alerts.php' ?>

<div class="row">

    <div class="col-sm-6">
        <div class="pnl">
            <form action="/dashboard/settings" method="post" enctype="multipart/form-data">
                <div class="pnl-header">
                    Autoaktualizacje aplikacji
                    <button type="submit" class="btn btn-success btn-sm pull-right">Zapisz</button>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="apk-version">Wersja</label>
                        <input type="text" class="form-control" name="version" id="version"
                               placeholder="Wersja" value="<?= $version ?>">
                    </div>
                    <div class="form-group">
                        <label for="apk-file">Plik APK</label>
                        <?php if (isset($apkLastModified)): ?>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <a href="/download"><span class="last-modified"><?=formatTimestamp($apkLastModified)?></span></a>
                                    <br>
                                    <small>MD5: <?=$apkMd5?></small>
                                </div>
                            </div>
                        <?php endif ?>
                        <input type="file" name="apk" id="apk">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'templates/dashboard/footer.php' ?>