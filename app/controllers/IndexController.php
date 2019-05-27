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
            $allLinks = $linkModel->getLinksList();
            $canReadPrivate = ServiceProvider::getService("Authentication")->hasPrivateLinksAccess();
            $links = array();
            foreach($allLinks as $link) {
                if ($link["private"] == true && ($link["userId"] == $userInfo["id"] || $canReadPrivate == true)) {
                    array_push($links, $link);
                }
                else if ($link["private"] ==false){
                    array_push($links, $link);
                }
            }
        }

        $page = $_GET["page"];
        $elementsOnPage = $_GET["elementsOnPage"];

        if ($page == "") {
            $page = 1;
        }
        if ($elementsOnPage == "") {
            $elementsOnPage = ServiceProvider::getService("Config")->getPaginationCount();
        }


        $linksToShow = array_slice($links, ($page-1)*$elementsOnPage, $elementsOnPage);
        $pagination = ServiceProvider::getService("Pagination")->generatePagination(count($links), $elementsOnPage, $page);

        $data->setData("pagination", $pagination);
        $data->setData("canEditLinks", $userRoleInfo["editLinks"]);
        $data->setData("canDeleteLinks", $userRoleInfo["deleteLinks"]);
        $data->setData("userId", $userInfo["id"]);
        $data->setData("links", $linksToShow);

        $this->view = "Main";

        $this->renderView();
    }


}