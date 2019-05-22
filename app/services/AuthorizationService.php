<?php


class AuthorizationService
{
    private $userInfo;
    private $roleInfo;

    public function __construct() {

    }

    public function getUserInfo() {

        return $this->userInfo;
    }




    public static function isLoggedIn() {
        return $_SESSION["userId"]!=0;
    }

    /**
     * @return void
     */
    public function getRoleInfo()
    {
        return $this->roleInfo;
    }
}