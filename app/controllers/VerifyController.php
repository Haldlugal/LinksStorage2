<?php


class VerifyController extends CommonController
{

    function process($params)
    {
        $userModel = new UserModel();
        if ($userModel->verifyUser($params["method"])>0){
            $this->data["message"] = "Your account successfully approved";
        }
        else {
            $this->data["message"] = "You cannot approve account with that link, It is either invalid or too old.
             You can resend approval letter if you want by clicking this link: Resend";
        }
        $this->view = "verify";
        $this->renderView();
    }
}