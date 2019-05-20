<?php
    require_once "ServiceProvider.php";

class App {

    public function run() {

        ServiceProvider::getService("Preload");

        $router = ServiceProvider::getService("Router");
        $data = $router->run();
        var_dump($data);
        if ($data["controller"] == "Controller") {
            self::redirect("main-page");
        }

        if (file_exists("app/controllers/".$data["controller"].".php")) {
            $controller = new $data["controller"];
        }
        else {
            self::redirect("error");
        }
        $controller->process($data);

    }

    public static function redirect($url) {
        header("Location: /$url");
        exit;
    }

}