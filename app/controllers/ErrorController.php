<?php


class ErrorController extends CommonController
{
    public function process($params)
    {
        header("HTTP/1.0 404 Not Found");
        $this->head["title"] = "Error 404";
        $this->view = "Page404";
        $this->data["error"] = "Hi, I am an error!";
        $this->renderView();
    }

}