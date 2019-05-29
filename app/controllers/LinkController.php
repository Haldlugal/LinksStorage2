<?php


class LinkController extends ElementsController {

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
        $userId = ServiceProvider::getService("Authorization")->getUserInfo()["id"];
        $data = ServiceProvider::getService("Data");

        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($_POST["linkPrivacy"] == "on") {
                $privacy = 1;
            } else $privacy = 0;

            if ($linkModel->isLinkUnique($userId, $_POST["linkUrl"])) {
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
        $userId = ServiceProvider::getService("Authorization")->getUserInfo()["id"];
        $data = ServiceProvider::getService("Data");
        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($_POST["pastLinkUrl"] == $_POST["linkUrl"] || $linkModel->isLinkUnique($userId, $_POST["linkUrl"])) {
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