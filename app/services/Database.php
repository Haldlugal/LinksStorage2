<?php

class Database
{
    private $connection;

    private $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    public function __construct() {
        $this->connect(Config::getDbHost(), Config::getDbLogin(), Config::getDbPassword(), Config::getDbName());
    }

    private function connect($host, $user, $password, $database)
    {
        if (!isset($this->connection)) {
            $this->connection = @new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                $this->settings
            );
        }
    }

    public function getConnection () {
        return $this->connection;
    }
}
