<?php
require_once "Autoloader.php";
require_once "ServiceProvider.php";
require_once "configs/DefaultServiceConfig.php";
class App {

    public function run() {
        $router = ServiceProvider::getService("Router");
        $data = $router->run();

        if ($data["controller"] == "Controller") {
            $data["controller"] = "IndexController";
        }
        else if ($data["controller"] == "ShowMyLinksController") {
            $data["controller"] = "IndexController";
            $data["operation"] = "showMyLinks";
        }

        $controller = new $data["controller"];

        if ($data["operation"] == "") {
            $controller($data);
        }
        else {
            call_user_func(array($controller, $data["operation"]),$data["data"]);
        }
        /*$accessControl = ServiceProvider::getService("AccessControl");
        if (!$accessControl->checkUserRights($_SESSION["userId"], $data["controller"], $data["operation"])) {

            self::redirect("error/403");
        }*/
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
