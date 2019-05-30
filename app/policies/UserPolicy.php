<?php


class UserPolicy
{
    public function edit($userId) {
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $rights = ServiceProvider::getService("Rights");
        $access = $rights->checkAccessLevel($userInfo["roleId"], "user", "edit");
        $userModel = new UserModel();
        if ($userModel->isAnonymous($userId)) {
            return false;
        }
        if ($access == "any"){
            return true;
        }
        else if ($access == "own") {
            if ($userInfo["id"] == $userId){
                return true;
            }
            else return false;
        }
        else {
            return false;
        }
    }

    public function editOther() {
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $rights = ServiceProvider::getService("Rights");
        $access = $rights->checkAccessLevel($userInfo["roleId"], "user", "edit");
        if ($access == "any"){
            return true;
        }
        else return false;
    }
}