<?php


class RegisterController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Register page", "description" => "Register");



        $this->view = "register";
    }
}