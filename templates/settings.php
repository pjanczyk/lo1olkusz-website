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

<?php include 'templates/header.php' ?>

<div class="page-header">
    <h1>Ustawienia</h1>
</div>

<?php include 'templates/alerts.php' ?>

<div class="row">

    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Autoaktualizacje aplikacji</h3>
            </div>
            <div class="panel-body">
                Wersja:
                <?php if (isset($apkVersion)): ?>
                    <code><?=$apkVersion?></code>
                <?php else: ?>
                    nie ustawiona
                <?php endif ?>
                <br/>
                Plik:
                <?php if (isset($apkFileLastModified)): ?>
                    <a href="/download"><?=$apkFileLastModified?></a>
                <?php else: ?>
                    nie ustawiony
                <?php endif ?>
            </div>
            <div class="panel-footer">
                <a class="show-next btn btn-link">Zmień</a>

                <form action="/dashboard/settings" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="apk-version">Wersja</label>
                        <input type="text" class="form-control" name="apk-version" id="apk-version" placeholder="Wersja">
                    </div>
                    <div class="form-group">
                        <label for="apk-file">Plik</label>
                        <input type="file" name="apk-file" id="apk-file">
                    </div>
                    <button type="submit" class="btn btn-default">Zmień</button>
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

<?php include 'templates/footer.php' ?>