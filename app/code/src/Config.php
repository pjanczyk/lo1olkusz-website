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

namespace pjanczyk\lo1olkusz;

class Config
{
    /** @var Config|null */
    private static $instance = null;

    /** @return Config */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $dataDir;
    private $logDir;
    private $dbConfig;

    private function __construct()
    {
        $config = require __DIR__ . '/../config.php';

        $this->dataDir = $config['data_dir'];
        $this->logDir = $config['log_dir'];

        $dbHost = $config['db_host'];
        $dbPort = $config['db_port'];
        $dbName = $config['db_database'];

        $this->dbConfig = new DatabaseConfig;
        $this->dbConfig->dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName}";
        $this->dbConfig->user = $config['db_user'];
        $this->dbConfig->password = $config['db_password'];
        $this->dbConfig->options = $config['db_options'];
    }

    public function getDatabaseConfig()
    {
        return $this->dbConfig;
    }

    public function getApkFilePath()
    {
        return $this->dataDir . '/apk';
    }

    public function getLogsPath()
    {
        return $this->logDir . '/cron.log';
    }
}