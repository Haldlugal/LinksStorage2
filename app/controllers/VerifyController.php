<?php


class VerifyController extends CommonController
{

    function process($params)
    {
        $userModel = new UserModel();
        if ($userModel->verifyUser($params["operation"])>0){
            $this->data["success"] = "Your account successfully approved";
        }
        else {
            $this->data["error"] = "You cannot approve account with that link, It is either invalid or too old.";
        }
        $this->view = "Verify";
        $this->renderView();
    }
}