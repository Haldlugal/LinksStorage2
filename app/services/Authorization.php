<?php


class Authorization
{
    private $userRole;
    private $userName;
    private $user;
    public function __construct() {
        /*Fill some information about user in this constructor: his common info and info about his permissions that will
        be contained in $userRole*/
    }

    public static function isLoggedIn() {
        return $_SESSION["userId"]!=0;
    }
}