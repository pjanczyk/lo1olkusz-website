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

use pjanczyk\lo1olkusz\Cron\LuckyNumberParser;
use pjanczyk\lo1olkusz\LuckyNumber;

require 'autoloader.php';
require_once 'libs/simple_html_dom.php';


class LuckyNumberParserTest extends PHPUnit_Framework_TestCase
{
    public function testExist()
    {
        $start = microtime(true);

        $dom = file_get_html(__DIR__ . '/correct_zast.html');

        $a = new LuckyNumberParser;
        $result = $a->getLuckyNumber($dom);

        $time_elapsed_secs = microtime(true) - $start;
        echo 'ReplacementsParserTest::testExist: ' . $time_elapsed_secs . PHP_EOL;

        $expected = new LuckyNumber;
        $expected->date = "2015-06-10";
        $expected->value = 8;
        $expected->lastModified = null;

        $this->assertEquals($expected, $result);
        $this->assertEquals([], $a->getErrors());
    }

    public function testNotExist()
    {
        $dom = file_get_html(__DIR__ . '/empty_zast.html');

        $a = new LuckyNumberParser;
        $result = $a->getLuckyNumber($dom);

        $this->assertEquals(null, $result);
        $this->assertEquals([], $a->getErrors());
    }

    public function testIncorrect()
    {
        $dom = file_get_html(__DIR__ . '/incorrect_zast.html');

        $a = new LuckyNumberParser;
        $result = $a->getLuckyNumber($dom);

        $this->assertEquals(null, $result);
        $this->assertGreaterThan(0, count($a->getErrors()));
    }
}
