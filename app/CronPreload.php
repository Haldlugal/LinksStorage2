<?php
require_once "ServiceProvider.php";
require_once $_SERVER['DOCUMENT_ROOT']."/app/Autoloader.php";

class CronPreload
{
    public static function run() {
        Autoloader::run();
        ServiceProvider::setDefaultServices();
    }
}