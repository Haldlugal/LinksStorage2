<?php


class RouterService {

    public function run() {
        $url = $_SERVER["REQUEST_URI"];
        $parsedUrl = $this->parseUrl($url);
        $controllerClass = $this->dashesToCamel(array_shift($parsedUrl)."Controller");
        $operation = array_shift($parsedUrl);
        return array("controller" => $controllerClass, "operation" => $operation, "data" => array_shift($parsedUrl));
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