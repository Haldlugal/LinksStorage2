<?php


class LogoutController extends CommonController {

    function process($params)
    {
        $_SESSION["userId"] = 0;
        App::redirect("");
    }
}