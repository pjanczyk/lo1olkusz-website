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

//Created on 2015-07-09

require_once 'classes/Config.php';

use pjanczyk\lo1olkusz\Config;

date_default_timezone_set('Europe/Warsaw');

$dirPath = Config::getDataDir() . $currentPage;
$urlBase = '/api/' . $currentPage;

include 'html/header.php' ?>

<div class="page-header"><h1><?=$pages[$currentPage]['title']?></h1></div>
<?=$dirPath?><br/>

<?php
$i = 1;
if ($handle = opendir($dirPath)): ?>
    <table class="table table-condensed" style="width: auto">
        <thead>
        <tr>
            <th>#</th>
            <th>file name</th>
            <th>last modified</th>
        </tr>
        </thead>
        <tbody>
        <?php while (false !== ($fileName = readdir($handle))):
            if ($fileName != "." && $fileName != ".."):
                $filePath = $dirPath . '/' . $fileName;
                $linkPath = $urlBase . '/' . $fileName ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><a href="<?= $linkPath ?>.json"><?= $fileName ?></a></td>
                    <td><?= date('Y-m-d G:i', filemtime($filePath)) ?></td>
                </tr>
            <?php endif;
        endwhile ?>
        </tbody>
    </table>
    <?php closedir($handle);
endif;

include 'html/footer.php';