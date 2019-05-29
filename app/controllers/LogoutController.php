<?php


class LogoutController extends CommonController {

    function __invoke()
    {
        $_SESSION["userId"] = 0;
        App::redirect("");
    }
}