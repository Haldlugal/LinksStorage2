<?php


class VerifyController extends CommonController
{
    function index($userId)
    {
        $data = ServiceProvider::getService("Data")->getData();
        $userModel = new UserModel();
        if ($userModel->verifyUser($userId)>0){
            $data["success"] = "Your account successfully approved";
        }
        else {
            $data["error"] = "You cannot approve account with that link, It is either invalid or too old.";
        }
        $this->view = "Verify";
        $this->renderView();
    }
}