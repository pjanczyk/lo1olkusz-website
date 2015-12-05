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

<?php include 'templates/dashboard/alerts.php' ?>
    <div class="btn-group" role="group">
        <a href="/dashboard/timetables/add" class="btn btn-default">Dodaj</a>
    </div>

    <table class="table table-bordered" style="width: auto">
        <thead>
        <tr>
            <th>Klasa</th>
            <th>Zmodyfikowano</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($timetables as $timetable): ?>
            <tr>
                <td><?= $timetable->class ?></td>
                <td><?= formatTimestamp($timetable->lastModified) ?></td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            Operacje
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="/dashboard/timetables/edit/<?= $timetable->class ?>">Edytuj</a>
                            </li>
                            <li><a class="timetable-delete"
                                   href="/dashboard/timetables/delete/<?= $timetable->class ?>">Usuń</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>


<?php include 'templates/dashboard/footer.php' ?>