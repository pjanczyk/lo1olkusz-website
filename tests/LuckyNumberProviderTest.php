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


require_once 'simple_html_dom.php';
include 'src/LuckyNumberProvider.php';


class LuckyNumberProviderTest extends PHPUnit_Framework_TestCase {

    public function testExist() {
        $dom = file_get_html(__DIR__.'/correct_zast.html');

        $a = new \pjanczyk\lo1olkusz\LuckyNumberProvider;
        $result = $a->getLuckyNumber($dom);

        $expected = new \pjanczyk\lo1olkusz\LuckyNumber;
        $expected->date = "2015-06-10";
        $expected->value = 8;
        $expected->lastModified = null;

        $this->assertEquals($expected, $result);
        $this->assertEquals([], $a->getErrors());
    }

    public function testNotExist() {
        $dom = file_get_html(__DIR__.'/empty_zast.html');

        $a = new \pjanczyk\lo1olkusz\LuckyNumberProvider;
        $result = $a->getLuckyNumber($dom);

        $this->assertEquals(null, $result);
        $this->assertEquals([], $a->getErrors());
    }

    public function testIncorrect() {
        $dom = file_get_html(__DIR__.'/incorrect_zast.html');

        $a = new \pjanczyk\lo1olkusz\LuckyNumberProvider;
        $result = $a->getLuckyNumber($dom);

        $this->assertEquals(null, $result);
        $this->assertGreaterThan(0, count($a->getErrors()));
    }


}
