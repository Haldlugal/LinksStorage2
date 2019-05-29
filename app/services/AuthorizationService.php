<?php


class AuthorizationService
{
    private $userInfo;

    public function __construct() {
        $userModel = new UserModel();
        if (self::isLoggedIn()) {
            $this->userInfo = $userModel->selectById($_SESSION["userId"]);
        }
    }

    public function getUserInfo() {
        return $this->userInfo;
    }

    public function isLoggedIn() {
        return $_SESSION["userId"]!=0;
    }


}