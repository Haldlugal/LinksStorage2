<?php


abstract class CommonController
{
    protected $data = array();
    protected $view = "";
    protected $head = array("title" => "", "description" => "");

    abstract function process($params);

    public function renderView() {
        require("app/views/Header.phtml");
        if ($this->view) {
            extract($this->data);
            require("app/views/".$this->view.".phtml");
        }
        require("app/views/Footer.phtml");
    }
}