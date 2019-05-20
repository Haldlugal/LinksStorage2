<?php


class RouterController extends CommonController
{
    protected $controller;

    public function process($url) {
        $parsedUrl = $this->parseUrl($url);
        if (empty($parsedUrl[0])) {
            $this->redirect("main-page");
        }
        $controllerClass = $this->dashesToCamel(array_shift($parsedUrl)."Controller");
        if (file_exists("controllers/".$controllerClass.".php")) {
            $this->controller = new $controllerClass;
        }
        else {
            $this->redirect("error");
        }
        $this->controller->process($parsedUrl);
        $this->data["title"] = $this->controller->head["title"];
        $this->data["description"] = $this->controller->head["description"];
        $this->view = "layout";
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