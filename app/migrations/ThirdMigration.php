<?php


class ThirdMigration
{
    public $version = 3;

    public function up() {
        echo "3";
        /*$database = ServiceProvider::getService("Database");
        $pdo = $database->getConnection();
        $statement = $pdo->prepare("CREATE TABLE IF NOT EXISTS tasks (
            task_id INT AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            start_date DATE,
            due_date DATE,
            status TINYINT NOT NULL,
            priority TINYINT NOT NULL,
            description TEXT,
            PRIMARY KEY (task_id)
        ) ");
        $statement->execute();*/
    }
}