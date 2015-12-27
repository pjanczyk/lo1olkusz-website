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

/** @var array(Timetable) $timetables */
?>

<?php include 'templates/dashboard/header.php' ?>

    <div class="page-header">
        <h1>Plany lekcji</h1>
    </div>

    <div>
        <a href="/dashboard/timetables/add" class="btn btn-default">Dodaj</a>
        <a href="/dashboard/timetables/import" class="btn btn-default">Importuj</a>
        <a href="/dashboard/timetables/bells" class="btn btn-default">Dzwonki</a>
    </div>
    <br>
    <div class="tbl tbl-centered" style="width: auto">
        <div class="tbl-row tbl-header">
            <div class="tbl-cell">Klasa</div>
            <div class="tbl-cell">Zmodyfikowano</div>
        </div>
        <?php foreach ($timetables as $timetable): ?>
            <a class="tbl-row" href="/dashboard/timetables/edit/<?= $timetable->class ?>">
                <div class="tbl-cell"><?= $timetable->class ?></div>
                <div class="tbl-cell last-modified"><?= formatTimestamp($timetable->lastModified) ?></div>
            </a>
        <?php endforeach ?>
    </div>


<?php include 'templates/dashboard/footer.php' ?>

