<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-19
 * Time: 22:02
 */

require 'autoloader.php';

use pjanczyk\sql\internal\DeleteBuilder;
use pjanczyk\sql\internal\InsertOrUpdateBuilder;
use pjanczyk\sql\internal\SelectBuilder;

class SqlBuilderTest extends PHPUnit_Framework_TestCase {

    public function testSelect() {
        $start = microtime(true);

        $builder = new SelectBuilder(null, "table", ["one", "two"]);
        $sql = $builder
            ->orderAsc("oa")
            ->orderDesc("od")
            ->where(['k1'=>'v1', 'k2'=>'v2'])
            ->buildSql();

        $time_elapsed_secs = microtime(true) - $start;
        echo 'elapsed: ' . $time_elapsed_secs . PHP_EOL;

        $correctSql = "SELECT one,two FROM table WHERE k1=v1 AND k2=v2 ORDER BY oa ASC,od DESC";

        $this->assertEquals($correctSql, $sql);

    }

    public function testInsertOrUpdate() {
        $start = microtime(true);

        $builder = new InsertOrUpdateBuilder(null, "table");
        $sql = $builder
            ->where(['k1'=>'v1', 'k2'=>'v2'])
            ->set(['s3'=>'v3','s4'=>'v4'])
            ->buildSql();

        $time_elapsed_secs = microtime(true) - $start;
        echo 'elapsed: ' . $time_elapsed_secs . PHP_EOL;

        $correctSql = "INSERT INTO table (k1,k2,s3,s4) VALUES (v1,v2,v3,v4) ON DUPLICATE KEY UPDATE s3=v3,s4=v4";

        $this->assertEquals($correctSql, $sql);
    }

    public function testDelete() {
        $start = microtime(true);

        $builder = new DeleteBuilder(null, "table");
        $sql = $builder
            ->where(['k1'=>'v1', 'k2'=>'v2'])
            ->buildSql();

        $time_elapsed_secs = microtime(true) - $start;
        echo 'elapsed: ' . $time_elapsed_secs . PHP_EOL;

        $correctSql = "DELETE FROM table WHERE k1=v1 AND k2=v2";

        $this->assertEquals($correctSql, $sql);

    }
}
