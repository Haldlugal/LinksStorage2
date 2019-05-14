<?php


class LoginController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Login page", "description" => "Login");

        /*Here is some login logic*/

        $this->view = "login";
    }
}