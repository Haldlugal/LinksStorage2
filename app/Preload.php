<?php
require_once "ServiceProvider.php";
require_once "app/configs/DefaultServiceConfig.php";
require_once "app/Autoloader.php";
class Preload {
    public static function run() {
        error_reporting(E_ALL & ~E_NOTICE);
        mb_internal_encoding("UTF-8");
        session_start();
        Autoloader::run();
        ServiceProvider::setDefaultServices();
    }


}

