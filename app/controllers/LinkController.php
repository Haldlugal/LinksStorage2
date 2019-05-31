<?php


class LinkController extends ElementsController {

    public function index() {
        $linkModel = new LinkModel();
        $data = ServiceProvider::getService("Data");
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $this->head = array("title" => "Main page", "description" => "Main");
        $allLinks = $linkModel->getReadableList($linkModel->getList());

        $pagination =  $this->getPagination($allLinks);
        $linksToShow = $this->getElementsToShow($allLinks);

        $data->setData("pagination", $pagination);
        $data->setData("userId", $userInfo["id"]);
        $data->setData("links", $linksToShow);

        $this->view = "LinksList";

        $this->renderView();
    }

    /*public function showMy() {
        $this->head = array("title" => "My Links", "description" => "Main");
        $linkModel = new LinkModel();
        $data = ServiceProvider::getService("Data");
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $links = $linkModel->getReadableList($linkModel->getListByUserId($userInfo["id"]));

        $pagination =  $this->getPagination($links);
        $linksToShow = $this->getElementsToShow($links);

        $data->setData("pagination", $pagination);
        $data->setData("userId", $userInfo["id"]);
        $data->setData("links", $linksToShow);

        $this->view = "LinksList";

        $this->renderView();
    }*/

    public function showList() {
        $this->head = array("title" => "My Links", "description" => "Main");
        $userId = $_GET["userId"];
        $linkModel = new LinkModel();
        $data = ServiceProvider::getService("Data");

        $links = $linkModel->getReadableList($linkModel->getListByUserId($userId));
        $pagination =  $this->getPagination($links);
        $linksToShow = $this->getElementsToShow($links);

        $data->setData("pagination", $pagination);
        $data->setData("userId", $userId);
        $data->setData("links", $linksToShow);

        $this->view = "LinksList";

        $this->renderView();
    }

    public function read($linkId) {
        $this->head = array("title" => "Read link", "description" => "Link");
        $linkModel = new LinkModel();
        $data = ServiceProvider::getService("Data");
        $link = $linkModel->get($linkId);
        $data->setData("link", $link);
        $this->view = "LinkRead";
        $this->renderView();
    }

    public function delete($linkId) {
        $linkModel = new LinkModel();
        $linkModel->delete($linkId);
        App::redirect("");
    }

    public function create() {
        $this->head = array("title" => "Edit link", "description" => "Link");
        $linkModel = new LinkModel();
        $userId = ServiceProvider::getService("Authentication")->getUserInfo()["id"];
        $data = ServiceProvider::getService("Data");
        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($_POST["linkPrivacy"] == "on") {
                $privacy = 1;
            } else $privacy = 0;

            if ($linkModel->isUnique($userId, $_POST["linkUrl"])) {
                $linkModel->create($_POST["userId"], $_POST["linkTitle"], $_POST["linkUrl"], $_POST["linkDescription"], $privacy);
                App::redirect("");
            }
            else {
                $data->setData("title", $_POST["linkTitle"]);
                $data->setData("url", $_POST["linkUrl"]);
                $data->setData("description", $_POST["linkDescription"]);
                $data->setData("private", $privacy);
                $data->setData("errorMessage", "Link with such url already exists");
            }
        }
        $this->view = "LinkCreate";
        $this->renderView();
    }

    public function edit($linkId) {
        $this->head = array("title" => "Create link", "description" => "Link");
        $linkModel = new LinkModel();
        $data = ServiceProvider::getService("Data");
        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            $userId = $_POST["userId"];
            if ($_POST["pastLinkUrl"] == $_POST["linkUrl"] || $linkModel->isUnique($userId, $_POST["linkUrl"])) {
                $linkModel->edit($_POST["linkId"], $_POST["linkTitle"], $_POST["linkUrl"], $_POST["linkDescription"], $_POST["linkPrivacy"]);
                App::redirect("link/read/" . $_POST["linkId"]);
            } else {
                $data->setData("linkId",$_POST["linkId"]);
                $data->setData("title", $_POST["linkTitle"]);
                $data->setData("url", $_POST["pastLinkUrl"]);
                $data->setData("description", $_POST["linkDescription"]);
                $data->setData("private", $_POST["linkPrivacy"]);
                $data->setData("errorMessage", "Link with such url already exists");
            }
        }
        else {
            $link = $linkModel->get($linkId);
            $data->setData("userId", $link["userId"]);
            $data->setData("title",$link["title"]);
            $data->setData("linkId",$link["id"]);
            $data->setData("description",$link["description"]);
            $data->setData("url",$link["url"]);
            $data->setData("private", $link["private"]);
        }
        $this->view = "LinkEdit";
        $this->renderView();
    }




}