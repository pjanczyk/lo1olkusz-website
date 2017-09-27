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

use pjanczyk\lo1olkusz\Cron\LuckyNumberParser;
use pjanczyk\lo1olkusz\Model\LuckyNumber;
use pjanczyk\lo1olkusz\SimpleHtmlDom\SimpleHtmlDom;

require_once __DIR__ . '/../autoloader.php';

class LuckyNumberParserTest extends PHPUnit_Framework_TestCase
{
    public function testExist()
    {
        $dom = SimpleHtmlDom::fromUrl(__DIR__ . '/correct.html');

        $a = new LuckyNumberParser;
        $result = $a->getLuckyNumber($dom);

        $expected = new LuckyNumber;
        $expected->date = "2016-06-10";
        $expected->value = 8;
        $expected->lastModified = null;

        $this->assertEquals($expected, $result);
        $this->assertEquals([], $a->getErrors());
    }

    public function testNotExist()
    {
        $dom = SimpleHtmlDom::fromString("<html></html>");

        $a = new LuckyNumberParser;
        $result = $a->getLuckyNumber($dom);

        $this->assertEquals(null, $result);
        $this->assertEquals([], $a->getErrors());
    }

    public function testIncorrect()
    {
        $dom = SimpleHtmlDom::fromUrl(__DIR__ . '/incorrect.html');

        $a = new LuckyNumberParser;
        $result = $a->getLuckyNumber($dom);

        $this->assertEquals(null, $result);
        $this->assertGreaterThan(0, count($a->getErrors()));
    }
}
