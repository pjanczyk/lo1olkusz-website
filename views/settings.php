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
?>

<?php include 'views/header.php' ?>

<div class="page-header"><h1>Settings</h1></div>

<?php include 'views/alerts.php' ?>

<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Autoupdate APK</h3>
            </div>
            <div class="panel-body">
                Version:
                <?php if (isset($apkVersion)): ?>
                    <code><?=$apkVersion?></code>
                <?php else: ?>
                    not set
                <?php endif ?>
                <br/>
                File:
                <?php if (isset($apkFileLastModified)): ?>
                    <a href="/lo1olkusz-app.apk"><?=$apkFileLastModified?></a>
                <?php else: ?>
                    not set
                <?php endif ?>
            </div>
            <div class="panel-footer">
                <a class="show-next btn btn-link">Change</a>

                <form action="/settings" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="apk-version">Version</label>
                        <input type="text" class="form-control" name="apk-version" id="apk-version" placeholder="Version">
                    </div>
                    <div class="form-group">
                        <label for="apk-file">File</label>
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

<?php include 'views/footer.php' ?>