<?php


class Router {

    protected $controller;

    public function run() {
        $url = $_SERVER["REQUEST_URI"];
        $parsedUrl = $this->parseUrl($url);
        $controllerClass = $this->dashesToCamel(array_shift($parsedUrl)."Controller");
        $method = array_shift($parsedUrl);
        return array("controller" => $controllerClass, "method" => $method, "data" => $parsedUrl);
        /*if (file_exists("app/controllers/".$controllerClass.".php")) {
            $this->controller = new $controllerClass;
        }
        else {
            self::redirect("error");
        }
        $data = $parsedUrl;
        $this->controller->process($data);*/
    }

    private function parseUrl($url) {
        $parsedUrl = parse_url($url);
        $parsedUrl["path"] = ltrim($parsedUrl["path"], "/");
        $parsedUrl["path"] = trim($parsedUrl["path"]);
        $data = explode("/", $parsedUrl["path"]);
        return $data;
    }

    private function dashesToCamel($string) {
        $string = str_replace("-", " ", $string);
        $string = ucwords($string);
        $string = str_replace(" ", "", $string);
        return $string;
    }

}