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

//Created on 2015-07-08

namespace pjanczyk\lo1olkusz\Cron;

use pjanczyk\lo1olkusz\Replacements;

require_once 'libs/simple_html_dom.php';

/**
 * Gets data of replacements from the official website, parsing it from html
 * (Unfortunately there is no available api for this, e.g. using json)
 */
class ReplacementsProvider
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Parses replacements from html.
     * If them cannot be find or they are in incorrect format returns null.
     * @param \simple_html_dom $dom
     * @return array(Replacements)|null
     */
    public function getReplacements($dom)
    {
        /** @var \simple_html_dom_node $itemPage */
        $itemPage = $dom->find('div[class=item-page]', 0);

        if ($itemPage === null) return null;

        /** @var \simple_html_dom_node $h4 */
        $h4 = $itemPage->find('h4', 0);
        /** @var \simple_html_dom_node $table */
        $table = $itemPage->find('table', 0);

        if ($h4 === null || $table === null) return null;

        //parse date
        $date = ReplacementsProvider::parseDate(trim($h4->plaintext));

        if ($date === null) {
            $this->errors[] = "incorrect date format: " . $h4->plaintext;
            return null;
        }

        //parse content
        $rows = $table->find('tr');

        /** @var array(Replacements) $replacements */
        $replacements = [];
        /** @var Replacements $current */
        $current = null;

        /** @var \simple_html_dom_node $row */
        foreach ($rows as $i => $row) {
            $cells = $row->find('th, td');

            if (count($cells) === 1) { //class name, e.g. | 2a |
                if ($current !== null) {
                    $current->value = json_encode($current->value);
                    $replacements[] = $current;
                }
                $current = new Replacements;
                $current->date = $date;
                $current->class = $cells[0]->plaintext;
                $current->value = [];
            } else if (count($cells) === 2) { //replacement entry, e.g. | 1 | j. niemiecki, mgr T. Wajdzik |
                if ($current === null) {
                    $this->errors[] = "row: {$i}, no class name occurred before replacement text";
                    continue; //skip this row
                }

                $hourText = $cells[0]->plaintext;
                $text = $cells[1]->plaintext;

                if (!is_numeric($hourText)) {
                    $this->errors[] = "row: {$i}, invalid hour no.: '{$hourText}'";
                    continue; //skip this row
                }
                $hour = intval($hourText);

                $current->value[$hour] = $text;
            }
        }

        if ($current != null) {
            $current->value = json_encode($current->value);
            $replacements[] = $current;
        }

        return $replacements;
    }

    /**
     * Parses a date from a text in format "d MMMM yyyy".
     * @param string $dateText
     * @return string|null date in format "yyyy-mm-dd"
     */
    private static function parseDate($dateText)
    {
        $array = explode(' ', $dateText);

        $months = ["stycznia" => 1,
            "lutego" => 2,
            "marca" => 3,
            "kwietnia" => 4,
            "maja" => 5,
            "czerwca" => 6,
            "lipca" => 7,
            "sierpnia" => 8,
            "września" => 9,
            "października" => 10,
            "listopada" => 11,
            "grudnia" => 12];

        if (count($array) === 3
            && is_numeric($array[0])
            && isset($months[$array[1]])
            && is_numeric($array[2])
        ) {

            $day = intval($array[0]);
            $month = $months[$array[1]];
            $year = intval($array[2]);

            $dateTime = new \DateTime;
            $dateTime->setDate($year, $month, $day);
            return $dateTime->format("Y-m-d");
        } else {
            return null;
        }
    }
}

