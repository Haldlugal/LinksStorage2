<?php


abstract class CommonController
{
    protected $data = array();
    protected $view = "";
    protected $head = array("title" => "", "description" => "");

    abstract function process($params);

    public function renderView() {
        $authorization = ServiceProvider::getService("Authorization");
        $this->data["userInfo"] = $authorization->getUserInfo();
        $this->data["loggedIn"] = $authorization->isLoggedIn();
        extract($this->data);
        require("app/views/Header.phtml");
        if ($this->view) {
            require("app/views/".$this->view.".phtml");
        }
        require("app/views/Footer.phtml");
    }
}