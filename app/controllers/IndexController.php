<?php


class IndexController extends CommonController {

    private $linkModel;
    private $userInfo;
    private $data;
    private $userRoleInfo;

    public function __construct()
    {
        $this->linkModel = new LinkModel();
        $this->userInfo = ServiceProvider::getService("Authorization")->getUserInfo();
        $this->userRoleInfo = ServiceProvider::getService("Authentication")->getRoleInfo();
        $this->data = ServiceProvider::getService("Data");
    }

    public function __invoke($params)
    {
        $this->head = array("title" => "Main page", "description" => "Main");

        $allLinks = $this->linkModel->getList();
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

        $this->data->setData("pagination", $pagination);
        $this->data->setData("canEditLinks", $userRoleInfo["editLinks"]);
        $this->data->setData("canDeleteLinks", $userRoleInfo["deleteLinks"]);
        $this->data->setData("userId", $userInfo["id"]);
        $this->data->setData("links", $links);

        $this->view = "Main";

        $this->renderView();
    }

    public function showMyLinks() {
        var_dump("her");
        $this->head = array("title" => "My Links", "description" => "Main");
        $links = $this->linkModel->getListByUserId($this->userInfo["id"]);
        $paginator =  $this->getPagination($links);
        $pagination = $paginator["pagination"];
        $linksToShow = $paginator["linksToShow"];
        $this->data->setData("pagination", $pagination);
        $this->data->setData("canEditLinks", $this->userRoleInfo["editLinks"]);
        $this->data->setData("canDeleteLinks", $this->userRoleInfo["deleteLinks"]);
        $this->data->setData("userId", $this->userInfo["id"]);
        $this->data->setData("links", $linksToShow);

        $this->view = "Main";

        $this->renderView();
    }

    private function getPagination($links) {
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
        return array("pagination" => $pagination, "linksToShow" => $linksToShow);
    }


}