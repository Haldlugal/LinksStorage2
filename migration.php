<?php
require_once "app/ServiceProvider.php";
require_once "app/configs/DefaultServiceConfig.php";
require_once "app/Autoloader.php";

chdir("/vagrant/LinksStorage");
Autoloader::run();
ServiceProvider::setDefaultServices();

if ($argv[1] == "migrate") {
    migrate();
}
else if ($argv[1] == "add") {
    addMigration($argv[2]);
}
else if ($argv[1] == "remove") {
    removeMigration($argv[2]);
}


function migrate() {
    $database = ServiceProvider::getService("Database");
    $pdo = $database->getConnection();
    $statement = $pdo->prepare("SELECT * FROM migrations ORDER BY date_created ASC");
    $statement->execute();
    $migrations = $statement->fetchAll();
    foreach($migrations as $migrationName) {
        $migration = new $migrationName["name"];
        $migration->up();
    }
    $statement = $pdo->prepare("DELETE FROM migrations");
    $statement->execute();
}

function addMigration($migrationName) {
    $database = ServiceProvider::getService("Database");
    $pdo = $database->getConnection();
    $statement = $pdo->prepare("INSERT INTO migrations (name) VALUES (:migrationName)");
    $data = array("migrationName"=>$migrationName);
    $statement->execute($data);
}

function removeMigration($migrationName) {
    $database = ServiceProvider::getService("Database");
    $pdo = $database->getConnection();
    $statement = $pdo->prepare("DELETE FROM migrations WHERE name = :migrationName");
    $data = array("migrationName"=>$migrationName);
    $statement->execute($data);

}