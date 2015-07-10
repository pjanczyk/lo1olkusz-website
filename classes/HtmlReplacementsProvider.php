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

namespace pjanczyk\lo1olkusz;

require_once 'simple_html_dom.php';

/**
 * Gets data of replacements from the official website, parsing it from html
 * (Unfortunately there is no available api for this, e.g. using json)
 */
class HtmlReplacementsProvider {

    private $errors = [];

    public function getErrors() {
        return $this->errors;
    }

    /**
     * Parses replacements from html.
     * If them cannot be find or they are in incorrect format returns null.
     * @param \simple_html_dom $dom
     * @return array in format
     *      [
     *          "date" => "yyyy-MM-dd",
     *          "replacements" => [
     *              string className => [
     *                  int hour => string replacementText,
     *                  ...
     *              ],
     *              ...
     *          ]
     *      ]
     * @throws \Exception on error
     */
	public function getReplacements($dom) {

        /** @var \simple_html_dom_node $itemPage */
        $itemPage = $dom->find('div[class=item-page]', 0);
		
		if ($itemPage === null) return null;

        /** @var \simple_html_dom_node $h4 */
        $h4 = $itemPage->find('h4', 0);
        /** @var \simple_html_dom_node $table */
        $table = $itemPage->find('table', 0);
		
		if ($h4 === null || $table === null) return null;
		
		//parse date
		$date = HtmlReplacementsProvider::parseDate(trim($h4->plaintext));

        if ($date === null) {
            throw new \Exception("incorrect date format: ".$h4->plaintext);
        }

        //parse content
        $rows = $table->find('tr');

        $replacements = [];

        $currentClassName = null;
        $ofCurrentClass = null;

        /** @var \simple_html_dom_node $row */
        foreach ($rows as $i => $row) {
            $cells = $row->find('th, td');

            if (count($cells) === 1) { //class name, e.g. | 2a |
                if ($currentClassName !== null) {
                    $replacements[$currentClassName] = $ofCurrentClass;
                }
                $currentClassName = $cells[0]->plaintext;
                $ofCurrentClass = [];
            }
            else if (count($cells) === 2) { //replacement entry, e.g. | 1 | j. niemiecki, mgr T. Wajdzik |
                if ($currentClassName === null) {
                    $currentClassName = "<nieznana>";
                    $ofCurrentClass = [];
                    $this->errors[] = "row: {$i}, no class name occured before replacement entry";
                }

                $hourText = $cells[0]->plaintext;
                $text = $cells[1]->plaintext;

                if (!is_numeric($hourText)) {
                    $this->errors[] = "row: {$i}, invalid hour no.: '{$hourText}'";
                    //skip this row
                    continue;
                }
                $hour = intval($hourText);

                $ofCurrentClass[$hour] = $text;
            }
        }

        if ($currentClassName != null) {
            $replacements[$currentClassName] = $ofCurrentClass;
        }

        return [
            "date" => $date->format("Y-m-d"),
            "replacements" => $replacements
        ];
	}

    /**
     * Parses a date from a text in format "d MMMM yyyy".
     * @param string $dateText
     * @return \DateTime|null
     */
	private static function parseDate($dateText) {
		$array = explode(' ', $dateText);

        $months = [ "stycznia" => 1,
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
            "grudnia" => 12 ];

        if (count($array) === 3
            && is_numeric($array[0])
            && isset($months[$array[1]])
            && is_numeric($array[2])) {

            $day = intval($array[0]);
            $month = $months[$array[1]];
            $year = intval($array[2]);

            $dateTime = new \DateTime;
            $dateTime->setDate($year, $month, $day);
            return $dateTime;
        }
        else {
            return null;
        }
	}
}

