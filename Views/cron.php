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

//Created on 2015-07-10
?>

<?php include 'Views/header.php' ?>

    <div class="page-header"><h1>Cron</h1></div>

    <div>
        <a id="run-cron" class="btn btn-default btn-sm" href="#">Run cron</a>
        <a id="clear-log" class="btn btn-danger btn-sm" href="#">Clear log</a>
    </div>
    <br/>

    <pre id="cron-log"><?=$logContent?></pre>

    <script>
        $("#clear-log").click(function() {
            $.post('cron', { 'clear-log': true }, function(data) {
                if (data == 'OK') {
                    $("#cron-log").empty();
                }
                else {
                    $(".page-header").after(
                        '<div class="alert alert-danger" role="alert">' + data + '</div>'
                    );
                }
            });
        });
        $("#run-cron").click(function() {
            $.post('cron', { 'run-cron': true }, function(data) {
                $(".page-header").after(
                    '<pre>' + data + '</pre>'
                );
            });
        })
    </script>

<?php include 'Views/footer.php' ?>