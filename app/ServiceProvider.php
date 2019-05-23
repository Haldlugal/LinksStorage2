<?php



class ServiceProvider {

    public static $services = array();

    public static function getService ($serviceName) {
        if (self::hasService($serviceName)) {
            if (is_string(self::$services[$serviceName])) {
                self::$services[$serviceName] = new self::$services[$serviceName];
            }
            return self::$services[$serviceName];
        }
    }

    public static function setService($serviceName, $serviceClassFunction) {
        self::$services[$serviceName] = call_user_func($serviceClassFunction);
    }

    public static function hasService($serviceName) {
        return array_key_exists($serviceName, self::$services);
    }

    public static function setDefaultServices() {
        self::$services = (new DefaultServiceConfig())->get();
    }
}