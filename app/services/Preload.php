<?php


class Preload {
    public function __construct() {
        spl_autoload_register("Preload::autoloadFunction");
        mb_internal_encoding("UTF-8");
        session_start();
    }

    static function autoloadFunction($class) {
        if(preg_match('/Controller$/', $class)) {
            require ("app/controllers/".$class.".php");
        }
        else if (preg_match('/Model$/', $class) || preg_match("/Database$/", $class)) {
            require ("app/models/".$class.".php");
        }
    }
}