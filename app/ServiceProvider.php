<?php



class ServiceProvider {

    public static $services = array();

    public static function getService ($serviceName) {
        return self::$services[$serviceName];
    }

    public static function setService($serviceName, $serviceClassFunction) {
        self::$services[$serviceName] = call_user_func($serviceClassFunction);
    }

    public static function hasService($serviceName) {
        return array_key_exists($serviceName, self::$services);
    }

    public static function setDefaultServices() {
        $defaultConfig = (new DefaultServiceConfig())->get();
        foreach ($defaultConfig as $serviceName=>$serviceClassName) {
            self::$services[$serviceName] = new $serviceClassName();
        }
    }
}