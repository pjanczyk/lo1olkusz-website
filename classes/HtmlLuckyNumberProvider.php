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

require_once '../simple_html_dom.php';

class HtmlLuckyNumberProvider {

    private $errors = [];

    public function getErrors() {
        return $this->errors;
    }

    public function getLuckyNumber($dom) {
        $text = HtmlLuckyNumberProvider::findText($dom);
        if ($text === null) {
            $this->errors[] = "Nie znaleziono na stronie danych o szczęśliwym numerku";
            return null;
        }

        try {
            return HtmlLuckyNumberProvider::parseText($text);
        }
        catch (\Exception $e) {
            $this->errors[] = "Niewłaściwy format danych: ".$e->getMessage();
            return null;
        }
    }

    /**
     * @param \simple_html_dom $dom
     * @return null|string , a text of a next paragraph after phrase "szczęśliwy numerek"
     *         or null if it wasn't found
     */
    private static function findText($dom) {
        $ps = $dom->find("div.column_right div.custom p");

        //find paragraph containing phrase "szczęśliwy numerek"
        $pTitleIndex = -1;
        foreach ($ps as $i => $p) {
            if (strcasecmp($p->plaintext, "SZCZĘŚLIWY NUMEREK") === 0) {
                $pTitleIndex = $i;
            }
        }

        //get context of the next paragraph
        if ($pTitleIndex !== -1 && isset($ps[$pTitleIndex+1])) {
            $p = $ps[$pTitleIndex+1];
            return $p->plaintext;
        }
        return null;
    }

    /**
     * Parses data of lucky number in format "31 XII - 13"
     * @param string $text
     * @return array
     * @throws \Exception when $text is in invalid format
     */
    private static function parseText($text) {
        $parts = explode('-', $text);
        if (count($parts) !== 2) {
            throw new \Exception("W tekście powinien być tylko jeden myślnik: '{$text}'");
        }

        $datePart = trim($parts[0]);
        $numberPart = trim($parts[1]);

        //parse date
        $array = explode(' ', $datePart);

        $months = [ "I" => 1,
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
            "XII" => 12 ];

        if (count($array) === 2
            && is_numeric($array[0])
            && isset($months[$array[1]])) {
            $day = intval($array[0]);
            $month = $months[$array[1]];
            $year = intval(date('Y'));

            $dateTime = new \DateTime;
            $dateTime->setDate($year, $month, $day);
            $date = $dateTime->format('Y-m-d');
        }
        else {
            throw new \Exception("Niewłaściwy format daty: '{$datePart}''");
        }

        //parse number
        if (!is_numeric($numberPart)) {
            throw new \Exception("Niewłaściwy format numeru: '{$numberPart}''");
        }
        $number = intval($numberPart);

        return [
            "date" => $date,
            "number" => $number
        ];
    }
}