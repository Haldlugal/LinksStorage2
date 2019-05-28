<?php
require_once "app/ServiceProvider.php";
require_once "app/configs/DefaultServiceConfig.php";
require_once "app/Autoloader.php";

Autoloader::run();
ServiceProvider::setDefaultServices();

$userModel=new UserModel();
foreach ($userModel->searchForVerificationLinks() as $link) {
    if ($userModel->checkIfLinkExpired($link["verificationText"])) {
        $userModel->deleteExpiredLink($link["verificationText"]);
    }
}
