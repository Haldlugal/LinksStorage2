<?php


class AuthenticationService
{
    private $roleInfo;
    private $userInfo;

    public function __construct()
    {
        $authorization = ServiceProvider::getService("Authorization");
        $this->userInfo = $authorization->getUserInfo();

        /*$this->roleInfo["showPrivateLinks"] = $rightsModel->userPermittedToReadPrivateLinks($userRoleId);
        $this->roleInfo["editLinks"] = $rightsModel->userPermittedToEditLink($userRoleId);
        $this->roleInfo["deleteLinks"] = $rightsModel->userPermittedToDeleteLink($userRoleId);*/

    }

    /**
     * @return void
     */
    public function getRoleInfo()
    {
        return $this->roleInfo;
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
        $linkInfo = $linkModel->getLink($linkId);
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
        $linkInfo = $linkModel->getLink($linkId);
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

    }
}