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

include 'classes/Config.php';

use pjanczyk\lo1olkusz\Config;

$path = Config::getLogDir() . 'cron.log';

if (isset($_POST['clear'])) {
    if (unlink($path)) {
        echo '<pre>Cleared</pre>';
    }
}

if (isset($_POST['run-cron'])) {
    echo '<pre>';
    include 'cron.php';
    echo '</pre>';
}

?>

<div class="page-header"><h1>Cron</h1></div>

<a id="clear-log" href="#">Clear log</a>
<a id="run-cron" href="#">Run cron</a>
<pre><?= file_exists($path) ? file_get_contents($path) : '' ?></pre>

<script>
    $("#clear-log").click(function() {
        $.post('', { 'clear': true }, function(data) {
            $(":root").replaceWith(data);
        });
    });
    $("#run-cron").click(function() {
        $.post('', { 'run-cron': true }, function(data) {
            $(":root").replaceWith(data);
        });
    })
</script>
