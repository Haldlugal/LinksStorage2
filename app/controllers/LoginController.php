<?php


class LoginController extends CommonController {

    function process($params)
    {
        $userModel = new UserModel();
        var_dump($userModel->checkUser("Test", "q"));

        $this->head = array("title" => "Login page", "description" => "Login");
        $this->view = "login";
    }
}