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

use pjanczyk\lo1olkusz\Model\Replacements;
use pjanczyk\lo1olkusz\SimpleHtmlDom\SimpleHtmlDom;

/**
 * Gets data of replacements from the official website, parsing it from html
 * (Unfortunately there is no available api for this, e.g. using json)
 */
class ReplacementsParser
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Parses replacements from html.
     * If them cannot be find or they are in incorrect format returns null.
     * @param SimpleHtmlDom $dom
     * @return Replacements[]|null
     */
    public function getReplacements($dom)
    {
        $table = $dom->root()->find('table', 0);
        if ($table == null) return null;

        $rows = $table->find('tr');

        /** @var Replacements[] $replacements */
        $replacements = [];
        /** @var Replacements|null $current */
        $current = null;

        foreach ($rows as $i => $row) {
        	if (count($row->find('th')) > 0) continue;

        	$cells = $row->find('td');

        	if (count($cells) != 7) {
		        $this->errors[] = "count($cells) != 7";
        		break;
	        }

	        $date = trim($cells[0]->text());
        	$class = trim($cells[1]->text());
        	$hour = trim($cells[2]->text());
        	$subject = trim($cells[3]->text());
        	$teacher = trim($cells[4]->text());
        	$classroom = trim($cells[5]->text());
			$details = trim($cells[6]->text());

			if (preg_match('/\d{4}-\d{2}-\d{2}/', $date) !== 1) {
				$this->errors[] = "Invalid date: $date";
				continue;
			}

	        if ($class == '') {
		        $this->errors[] = "Empty class: $class";
		        continue;
	        }
	        $class = mb_strtolower($class);

	        if (preg_match('/\d+/', $hour) !== 1) {
		        $this->errors[] = "Invalid hour: $hour";
				continue;
			}
	        $hour = intval($hour);

	        $index = $date . '$$' . $class;
	        if (isset($replacements[$index])) {
		        $currentRepl = $replacements[$index];
	        } else {
		        $currentRepl = new Replacements;
		        $currentRepl->date = $date;
		        $currentRepl->class = $class;
		        $currentRepl->value = [];
		        $replacements[$index] = $currentRepl;
	        }

	        $fields = [];
			if ($subject != '') $fields[] = $subject;
			if ($teacher != '') $fields[] = $teacher;
			if ($classroom != '') $fields[] = "sala $classroom";
			if ($details != '') $fields[] = $details;

	        $currentRepl->value[$hour] = join(', ', $fields);
        }

        return array_values($replacements);
    }

}

