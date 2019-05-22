<?php

require_once "app/configs/DefaultServiceConfig.php";
require_once "app/Autoloader.php";
class Preload {
    public static function run() {
        mb_internal_encoding("UTF-8");
        session_start();
        Autoloader::run();
        ServiceProvider::setDefaultServices();
    }


}

