<?php
/**
 * Copyright (C) 2016  Piotr Janczyk
 *
 * This file is part of lo1olkusz unofficial app - website.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\lo1olkusz\Cron;

use Exception;
use pjanczyk\lo1olkusz\Model\LuckyNumber;
use pjanczyk\lo1olkusz\SimpleHtmlDom\SimpleHtmlDom;

class LuckyNumberParser
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param SimpleHtmlDom $dom
     * @return LuckyNumber|null
     */
    public function getLuckyNumber($dom)
    {
        $text = LuckyNumberParser::findText($dom);
        if ($text === null) {
            return null;
        }

        try {
            return LuckyNumberParser::parseText($text);
        } catch (Exception $e) {
            $this->errors[] = "Unsupported format: " . $e->getMessage();
            return null;
        }
    }

    /**
     * @param SimpleHtmlDom $dom
     * @return null|string , a text of a next paragraph after phrase "szczęśliwy numerek"
     *         or null if it wasn't found
     */
    private static function findText($dom)
    {
        $ps = $dom->root()->find("div.column_right div.custom p");

        //find paragraph containing phrase "szczęśliwy numerek"
        $pTitleIndex = -1;
        foreach ($ps as $i => $p) {
            if (strcasecmp($p->text(), "SZCZĘŚLIWY NUMEREK") === 0) {
                $pTitleIndex = $i;
            }
        }

        //get context of the next paragraph
        if ($pTitleIndex !== -1 && isset($ps[$pTitleIndex + 1])) {
            $p = $ps[$pTitleIndex + 1];
            return $p->text();
        }
        return null;
    }

    /**
     * Parses data of lucky number in format "31 XII - 13"
     * @param string $text
     * @return \pjanczyk\lo1olkusz\Model\LuckyNumber
     * @throws Exception when $text is in invalid format
     */
    private static function parseText($text)
    {
        $ln = new LuckyNumber;

        $parts = explode('-', $text);
        if (count($parts) !== 2) {
            throw new Exception("There should be only 1 dash in phrase: '{$text}'");
        }

        $datePart = trim($parts[0]);
        $valuePart = trim($parts[1]);

        //parse date
        $array = explode(' ', $datePart);

        $months = ["I" => 1,
            "II" => 2,
            "III" => 3,
            "IV" => 4,
            "V" => 5,
            "VI" => 6,
            "VII" => 7,
            "VIII" => 8,
            "IX" => 9,
            "X" => 10,
            "XI" => 11,
            "XII" => 12];

        if (count($array) === 2
            && is_numeric($array[0])
            && isset($months[$array[1]])
        ) {
            $day = intval($array[0]);
            $month = $months[$array[1]];
            $year = intval(date('Y'));

            $dateTime = new \DateTime;
            $dateTime->setDate($year, $month, $day);
            $ln->date = $dateTime->format('Y-m-d');
        } else {
            throw new Exception("Incorrect date format: '{$datePart}'");
        }

        //parse number
        if (!is_numeric($valuePart)) {
            throw new Exception("Incorrect number format: '{$valuePart}'");
        }
        $ln->value = intval($valuePart);

        return $ln;
    }
}