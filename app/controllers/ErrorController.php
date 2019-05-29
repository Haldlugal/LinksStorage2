<?php


class ErrorController extends CommonController
{
    public function error403() {
        header("HTTP/1.0 403 Access Forbidden");
        $this->head["title"] = "Error 403";
        $this->view = "Page403";
        $this->renderView();
    }

    public function error404() {
        header("HTTP/1.0 404 Not Found");
        $this->head["title"] = "Error 404";
        $this->view = "Page404";
        $this->renderView();
    }
}