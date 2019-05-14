<?php
mb_internal_encoding("UTF-8");

function autoloadFunction($class) {
    if(preg_match('/Controller$/', $class)) {
        require ("controllers/".$class.".php");
    }
    else if (preg_match('/Model$/', $class) || preg_match("/Database$/", $class)) {
        require ("models/".$class.".php");
    }
    else if (preg_match('/Router$/', $class)) {
        require ("routers/".$class.".php");
    }
}

spl_autoload_register("autoloadFunction");

Database::connect("localhost", "root", "R00twala", "linkstorage");



$router = new RouterController();
$router->process($_SERVER["REQUEST_URI"]);
$router->renderView();
?>
