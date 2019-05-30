<?php


class LogoutController extends CommonController {

    function index()
    {
        $_SESSION["userId"] = 0;
        App::redirect("");
    }
}