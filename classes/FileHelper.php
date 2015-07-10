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

//Created on  2015-07-10

namespace pjanczyk\lo1olkusz;


class FileHelper {

    /**
     * Creates all parent directories needed for creating a file
     * @param string $filePath Path of the file
     */
    public static function createParentDirectories($filePath) {
        $dirPath = dirname($filePath);
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
    }

    /**
     * If a file exists, compares its content with $newContent
     * and if they differ, updates the file.
     * If the file does not exist, creates it.
     * @param string $path
     * @param string $newContent
     * @return bool, true if the file has been updated or created
     */
    public static function updateFile($path, $newContent) {
        $updated = false;

        if (!file_exists($path)) {
            $file = fopen($path, 'w');
            if ($file !== false) {
                fwrite($file, $newContent);
                fclose($file);
                $updated = true;
            }
        }
        else {
            $file = fopen($path, 'r+');
            if ($file !== false) {
                $oldContent = fread($file, filesize($path));
                if (strcmp($newContent, $oldContent) !== 0) { //different
                    fseek($file, 0);
                    fwrite($file, $newContent);
                    $updated = true;
                }
                fclose($file);
            }
        }

        return $updated;
    }
}