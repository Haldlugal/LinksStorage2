<?php


class CronController extends CommonController
{

    function process($params)
    {
        $userModel=new UserModel();
        foreach ($userModel->searchForVerificationLinks() as $link) {
            if ($userModel->checkIfLinkExpired($link["verificationText"])) {
                $userModel->deleteExpiredLink($link["verificationText"]);
            }
        }
    }
}