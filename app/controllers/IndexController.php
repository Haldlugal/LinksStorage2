<?php


class IndexController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Main page", "description" => "Main");

        $linkModel = new LinkModel();
        $authorization = ServiceProvider::getService("Authorization");
        $authentication = ServiceProvider::getService("Authentication");
        $data = ServiceProvider::getService("Data");
        $userInfo = $authorization->getUserInfo();
        $userRoleInfo = $authentication->getRoleInfo();



        if ($params["operation"] == "myLinks") {
            $links = $linkModel->getLinksListByUserId($userInfo["id"]);
        }
        else {
            $links = $linkModel->getLinksList();
        }

        $data->setData("showPrivateLinks", $userRoleInfo["showPrivateLinks"]);
        $data->setData("canEditLinks", $userRoleInfo["editLinks"]);
        $data->setData("canDeleteLinks", $userRoleInfo["deleteLinks"]);
        $data->setData("userId", $userInfo["id"]);
        $data->setData("links", $links);

        $this->view = "Main";

        $this->renderView();
    }
}