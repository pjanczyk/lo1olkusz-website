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

function printFilesList($dirPath, $urlPath = null) {
    if ($urlPath === null) {
        $urlPath = $dirPath;
    }

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
                    $linkPath = $urlPath . '/' . $fileName ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><a href="<?= $linkPath ?>"><?= $fileName ?></a></td>
                        <td><?= date('Y-m-d G:i', filemtime($filePath)) ?></td>
                    </tr>
                <?php endif;
            endwhile ?>
            </tbody>
        </table>
        <?php closedir($handle);
    endif;
}




if (isset($_GET['what'])) {
    if ($_GET['what'] == 'ln') {
        $what = 'ln';
        $title = 'Lucky numbers';
    }
    else if ($_GET['what'] == 'replacements') {
        $what = 'replacements';
        $title = 'Replacements';
    }
}


if (isset($what)) {
    include 'html/header.html';
    echo '<h4>' . $title .'</h4>';
    echo Config::getDataDir() . $what . '<br/>';
    printFilesList(Config::getDataDir() . $what, $what);
    include 'html/footer.html';
}
else {
    header("HTTP/1.0 404 Not Found");
}