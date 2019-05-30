<?php


class LinkPolicy
{
    public function edit($link) {
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $rights = ServiceProvider::getService("Rights");
        $access = $rights->checkAccessLevel($userInfo["roleId"], "link", "edit");
        if ($access == "any"){
            return true;
        }
        else if ($access == "own") {
            $linkModel = new LinkModel();
            $linkInfo = $linkModel->get($link);
            if ($userInfo["id"] == $linkInfo["userId"]){
                return true;
            }
            else return false;
        }
        else {
            return false;
        }
    }

    public function delete($link) {
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $rights = ServiceProvider::getService("Rights");
        $access = $rights->checkAccessLevel($userInfo["roleId"], "link", "delete");
        if ($access == "any"){
            return true;
        }
        else if ($access == "own") {
            $linkModel = new LinkModel();
            $linkInfo = $linkModel->get($link);
            if ($userInfo["id"] == $linkInfo["userId"]){
                return true;
            }
            else return false;
        }
        else {
            return false;
        }
    }

    public function read($link) {
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $rights = ServiceProvider::getService("Rights");
        $access = $rights->checkAccessLevel($userInfo["roleId"], "link", "read");
        if ($access == "any"){
            return true;
        }
        else if ($access == "own") {
            $linkModel = new LinkModel();
            $linkInfo = $linkModel->get($link);
            if ($linkInfo["private"] == 1) {
                if ($userInfo["id"] == $linkInfo["userId"]){
                    return true;
                }
                else return false;
            }
            else return true;
        }
        else {
            return false;
        }
    }
}