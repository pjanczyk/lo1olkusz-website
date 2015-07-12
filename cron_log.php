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

include 'html/header.html';

include 'classes/Config.php';

use pjanczyk\lo1olkusz\Config;

$path = Config::getLogDir() . 'cron.log';

if (isset($_POST['clear'])) {
    unlink($path);
}

?>

<h4>Cron log</h4>
<a href="javascript:$.post('/clear-cron-log');$('pre').empty()">Clear</a>
<pre>
    <?= file_get_contents($path) ?>
</pre>

<?php
include 'html/footer.html';