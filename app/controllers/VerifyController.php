<?php


class VerifyController extends CommonController
{
    function index()
    {
        $data = ServiceProvider::getService("Data");
        $userModel = new UserModel();
        $verificationText = $_GET["verificationText"];
        if ($userModel->verifyUser($verificationText)>0){
            $data->setData("success","Your account successfully approved" );
        }
        else {
            $data->setData("error", "You cannot approve account with that link, It is either invalid or verification link is expired.");
        }

        $this->view = "Verify";
        $this->renderView();
    }
}