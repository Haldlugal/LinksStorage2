<?php


class IndexController extends ElementsController {

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
        /*$canReadPrivate = ServiceProvider::getService("Authentication")->hasPrivateLinksAccess();
        $links = array();
        foreach($allLinks as $link) {
            if ($link["private"] == true && ($link["userId"] == $userInfo["id"] || $canReadPrivate == true)) {
                array_push($links, $link);
            }
            else if ($link["private"] ==false){
                array_push($links, $link);
            }
        }*/

        $pagination =  $this->getPagination($allLinks);
        $linksToShow = $this->getElementsToShow($allLinks);
        $this->data->setData("pagination", $pagination);
        //$this->data->setData("canEditLinks", $userRoleInfo["editLinks"]);
        //$this->data->setData("canDeleteLinks", $userRoleInfo["deleteLinks"]);
        $this->data->setData("userId", $this->userInfo["id"]);
        $this->data->setData("links", $linksToShow);

        $this->view = "Main";

        $this->renderView();
    }

    public function showMyLinks() {
        $this->head = array("title" => "My Links", "description" => "Main");
        $links = $this->linkModel->getListByUserId($this->userInfo["id"]);

        $pagination =  $this->getPagination($links);
        $linksToShow = $this->getElementsToShow($links);

        $this->data->setData("pagination", $pagination);
        $this->data->setData("canEditLinks", $this->userRoleInfo["editLinks"]);
        $this->data->setData("canDeleteLinks", $this->userRoleInfo["deleteLinks"]);
        $this->data->setData("userId", $this->userInfo["id"]);
        $this->data->setData("links", $linksToShow);

        $this->view = "Main";

        $this->renderView();
    }







}