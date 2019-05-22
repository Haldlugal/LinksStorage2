<?php


class Autoloader
{
    public static function run() {
        spl_autoload_register("self::autoloadFunction");
    }

    static function autoloadFunction($class) {
        try {
            if (preg_match('/Controller$/', $class)) {
                if (!file_exists("app/controllers/" . $class . ".php")) {
                    throw new MissingControllerException();
                }
                require("app/controllers/" . $class . ".php");
            }
            else if (preg_match('/Model$/', $class)) {
                require("app/models/" . $class . ".php");
            }
            else if (preg_match('/Service$/', $class)) {
                require("app/services/" . $class . ".php");
            }
            else if (preg_match('/Config$/', $class)) {
                require("app/services/" . $class . ".php");
            }
        }
        catch (MissingObjectException $exception) {
            //App::redirect("error");
        }
    }
}
class MissingObjectException extends Exception {}