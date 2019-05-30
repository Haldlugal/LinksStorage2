<?php


class MigrationService
{
    public function add() {
        /*add migration name to db*/
    }

    public function migrate() {
        /*run all migrations in date order*/
    }

    public function rollback() {
        /*run the latest applied migration's down method*/
    }
}