<?php
require_once "Autoloader.php";
require_once "ServiceProvider.php";
require_once "configs/DefaultServiceConfig.php";
class App {

    public function run() {

        $router = ServiceProvider::getService("Router");
        $data = $router->run();
        $accessControl = ServiceProvider::getService("AccessControl");

        $controllerName = $data["controller"]."Controller";
        $controller = new $controllerName;
        if (!$accessControl->checkRights($data["controller"], $data["action"], $data["data"])) {
            self::redirect("error403");
        }
        call_user_func(array($controller, $data["action"]),$data["data"]);
    }

    public function boot() {
        error_reporting(E_ALL & ~E_NOTICE);
        mb_internal_encoding("UTF-8");
        session_start();
        Autoloader::run();
        ServiceProvider::setDefaultServices();
    }

    public static function redirect($url) {
        header("Location: /$url");
        exit;
    }
}
