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

//Created on 2015-07-10


require_once __DIR__.'/../simple_html_dom.php';
include __DIR__.'/../classes/ReplacementsProvider.php';


class ReplacementsProviderTest extends PHPUnit_Framework_TestCase {

    public function testExist() {
        $dom = file_get_html(__DIR__.'/correct_zast.html');

        $a = new \pjanczyk\lo1olkusz\ReplacementsProvider;
        $result = $a->getReplacements($dom);
        $resultJson = json_encode($result);
        $correctJson = '{"date":"2015-06-23","replacements":{"1a":{"5":"matematyka, mgr R. Dylewska"},"1b":{"1":"zaczyna o 9.55"},"1f":{"7":"gr. N6- j.niem, mgr T. Wajdzik"},"2a":{"1":"gr. N9- j.niem, mgr T. Wajdzik","8":"gr. N1- j.niem, mgr T. Wajdzik"},"2d":{"1":"gr. N9- j.niem, mgr T. Wajdzik","2":"gr. N4- j.niem, mgr T. Wajdzik"},"2e":{"1":"gr. N9- j.niem, mgr T. Wajdzik"}}}';

        $this->assertEquals($correctJson, $resultJson);
        $this->assertEquals([], $a->getErrors());
    }

    public function testNotExist() {
        $dom = file_get_html(__DIR__.'/empty_zast.html');

        $a = new \pjanczyk\lo1olkusz\ReplacementsProvider;
        $result = $a->getReplacements($dom);

        $this->assertEquals(null, $result);
        $this->assertEquals([], $a->getErrors());
    }

    public function testIncorrect() {
        $dom = file_get_html(__DIR__.'/incorrect_zast.html');

        $a = new \pjanczyk\lo1olkusz\ReplacementsProvider;
        $result = $a->getReplacements($dom);

        $this->assertEquals(null, $result);
        $this->assertGreaterThan(0, count($a->getErrors()));
    }


}
