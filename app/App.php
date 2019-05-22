<?php
    require_once "ServiceProvider.php";
    require_once "Preload.php";

class App {

    public function run() {

        Preload::run();

        ServiceProvider::setService("Database", function(){
            $settings = [
                "host" => "localhost",
                "login" => "root",
                "password" => "R00twala",
                "dbName" => "linkstorage"
            ];
            return new PDOService($settings);
        });

        $router = ServiceProvider::getService("Router");

        $data = $router->run();

        if ($data["controller"] == "Controller") {
            $data["controller"] = "IndexController";
        }

        $controller = new $data["controller"];

        /*$accessControl = ServiceProvider::getService("AccessControl");
        $accessControl->checkUserRights($_SESSION["userId"], $data["controller"], $data["operation"]);*/

        $controller->process($data);

    }

    public static function redirect($url) {
        header("Location: /$url");
        exit;
    }

}

class MissingClassException extends Exception { }