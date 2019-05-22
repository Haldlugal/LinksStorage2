<?php

class PDOService
{
    private $connection;

    private $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    public function __construct($settings=array()) {
        if (empty($settings)) {
            $config = new Config();
            $this->connect($config->getDbHost(), $config->getDbLogin(), $config->getDbPassword(), $config->getDbName());
        }
        else {
            $this->connect($settings["host"], $settings["login"], $settings["password"], $settings["dbName"]);
        }

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
