<?php
require_once "services/Database.php";
require_once "services/Mailer.php";
require_once "services/Authorization.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/Config.php';

class ServiceProvider {

    private static $services = array();

    public static function getService ($service) {
        /*check $service key in $services array. If it exists return instance by this key, else create instance of such service in this array*/
        require_once "services/".$service.".php";
        if (array_key_exists($service, self::$services)){
            return self::$services[$service];
        }
        else {
            self::$services[$service] = new $service;
            return self::$services[$service];
        }
    }


}