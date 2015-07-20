<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-19
 * Time: 22:02
 */

require 'autoloader.php';

use pjanczyk\sql\SqlBuilder;

class SqlBuilderTest extends PHPUnit_Framework_TestCase {

    public function test1() {
        $sql = SqlBuilder::select("table", ["one", "two"])
            ->orderAsc("column1")
            ->orderDesc("column2")
            ->where("where")
            ->sql();

        $correctSql = "SELECT `one`,`two` FROM `table` WHERE where ORDER BY `column1` ASC,`column2` DESC";

        $this->assertEquals($correctSql, $sql);
        echo 'POJ';
    }
}
