<?php


class AuthenticationService
{
    private $roleInfo;
    private $userInfo;

    public function __construct()
    {
        $authorization = ServiceProvider::getService("Authorization");
        $this->userInfo = $authorization->getUserInfo();
    }

    public function getRoleInfo()
    {
        return $this->roleInfo;
    }

    public function canEditOtherUsers() {
        $rightsModel = new RightsModel();
        if ($rightsModel->userPermittedToEditUser($this->userInfo["roleId"]) == "any") {
            return true;
        }
        else return false;
    }

    /*returns any, own or no*/
    private function canEditLinks() {
        $rightsModel = new RightsModel();
        return $rightsModel->userPermittedToEditLink($this->userInfo["roleId"]);
    }
    /*returns any, own or no*/
    private function canDeleteLinks() {
        $rightsModel = new RightsModel();
        return $rightsModel->userPermittedToDeleteLink($this->userInfo["roleId"]);
    }

    public function canEditThisLink($linkId) {
        $linkModel = new LinkModel();
        $linkInfo = $linkModel->get($linkId);
        $canEditLinks = $this->canEditLinks();
        if ($canEditLinks == "any"){
            return true;
        }
        else if($canEditLinks == "own" && $linkInfo["userId"] == $this->userInfo["id"]) {
            return true;
        }
        else return false;
    }
    public function canDeleteThisLink($linkId) {
        $linkModel = new LinkModel();
        $linkInfo = $linkModel->get($linkId);
        $canDeleteLinks = $this->canDeleteLinks();
        if ($canDeleteLinks == "any"){
            return true;
        }
        else if($canDeleteLinks == "own" && $linkInfo["userId"] == $this->userInfo["id"]) {
            return true;
        }
        else return false;
    }

    public function hasPrivateLinksAccess() {
        $rightsModel = new RightsModel();
        return $rightsModel->userPermittedToReadPrivateLinks($this->userInfo["roleId"]);
    }

    public function canReadUsersList() {
        $rightsModel = new RightsModel();
        return $rightsModel->userPermittedToReadUsersList($this->userInfo["roleId"]);
    }

    public function canEditThisUser($userId) {
        $rightsModel = new RightsModel();
        $rightToEdit = $rightsModel->userPermittedToEditUser($this->userInfo["roleId"]);
        if ($rightToEdit == "any") {
            return true;
        }
        else if ($rightToEdit == "own") {
            if($this->userInfo["id"] == $userId) {
                return true;
            }
        }
        return false;
    }

    public function canReadThisLink($linkId) {
        if ($this->hasPrivateLinksAccess()) {
            return true;
        }
        $linkModel = new LinkModel();
        $linkInfo = $linkModel->get($linkId);
        $isLinkPrivate = $linkInfo["private"];
        if ($isLinkPrivate && $linkInfo["userId"]==$this->userInfo["id"]) {
            return true;
        }
        else return false;
    }
}