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

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\framework\Application;
use pjanczyk\framework\Controller;
use pjanczyk\lo1olkusz\Models\SettingsModel;
use pjanczyk\lo1olkusz\Models\TimetablesModel;

class SettingsController extends Controller
{
    public function index()
    {
        $modelSettings = new SettingsModel($this->db);

        $apkPath = Application::getInstance()->getConfig()->getDataDir() . 'apk';

        $alerts = [];

        if (isset($_FILES['apk-file'])
            && $_FILES['apk-file']['error'] == UPLOAD_ERR_OK
        ) {

            $tmpName = $_FILES['apk-file']["tmp_name"];
            if (move_uploaded_file($tmpName, $apkPath)) {
                $alerts[] = 'Changed APK file';
            }
        }

        if (isset($_POST['apk-version'])) {
            if ($modelSettings->setValue('version', $_POST['apk-version'])) {
                $alerts[] = 'Changed APK version';
            }
        }

        $modelTimetables = new TimetablesModel($this->db);

        if (isset($_POST['edit'], $_POST['class'], $_POST['timetable'])) {
            if ($modelTimetables->set($_POST['class'], $_POST['timetable'])) {
                $alerts[] = "Saved timetable of \"{$_POST['class']}\"";
            }
        } else if (isset($_POST['delete'], $_POST['class'])) {
            if ($modelTimetables->delete($_POST['class'])) {
                $alerts[] = "Deleted timetable of \"{$_POST['class']}\"";
            }
        }

        /* views */
        $template = $this->includeView('settings');
        $template->alerts = $alerts;

        $settings = $modelSettings->getAll();
        if (isset($settings['version'])) {
            $template->apkVersion = $settings['version'];
        }
        if (file_exists($apkPath)) {
            $template->apkFileLastModified = date('Y-m-d H:i:s', filemtime($apkPath));
        }

        $template->timetables = $modelTimetables->getAll([TimetablesModel::FIELD_CLASS, TimetablesModel::FIELD_LAST_MODIFIED]);

        $template->render();
    }

    public function add_timetable()
    {
        $template = $this->includeView('timetable_edit');
        $template->timetable = null;
        $template->render();
    }

    public function edit_timetable($class)
    {
        $model = new TimetablesModel($this->db);

        $template = $this->includeView('timetable_edit');
        $template->timetable = $model->get($class);
        $template->render();
    }

    public function delete_timetable($class)
    {
        $model = new TimetablesModel($this->db);

        $timetable = $model->get($class);
        if ($timetable !== null) {
            $template = $this->includeView('timetable_delete');
            $template->timetable = $timetable;
            $template->render();
        } else {
            $this->index();
        }
    }
}

