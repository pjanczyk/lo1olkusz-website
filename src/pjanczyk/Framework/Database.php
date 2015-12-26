<?php

namespace pjanczyk\Framework;

class Database
{
    /** @var \PDO */
    private static $db;

    public static function init(DatabaseConfig $dbConfig)
    {
        self::$db = new \PDO(
            $dbConfig->dsn,
            $dbConfig->user,
            $dbConfig->password,
            $dbConfig->options);
    }

    public static function get()
    {
        return self::$db;
    }
}