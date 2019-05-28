<?php


abstract class CommonController
{
    protected $view = "";
    protected $head = array("title" => "", "description" => "");

    public function renderView() {
        require("app/views/Header.phtml");
        if ($this->view) {
            require("app/views/".$this->view.".phtml");
        }
        require("app/views/Footer.phtml");
    }
}