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
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $linkModel = new LinkModel();
            $link = $linkModel->get($linkId);
            echo json_encode($link);
        }
        else {
            $this->head = array("title" => "Read link", "description" => "Link");
            $linkModel = new LinkModel();
            $data = ServiceProvider::getService("Data");
            $link = $linkModel->get($linkId);
            $data->setData("link", $link);
            $this->view = "LinkRead";
            $this->renderView();
        }
    }

    public function delete($linkId) {
        $linkModel = new LinkModel();
        $linkModel->delete($linkId);
        App::redirect("");
    }

    public function create() {
        $this->head = array("title" => "Create link", "description" => "Link");
        $linkModel = new LinkModel();
        $userId = ServiceProvider::getService("Authentication")->getUserInfo()["id"];
        $data = ServiceProvider::getService("Data");
        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($_POST["linkPrivacy"] == "on") {
                $privacy = 1;
            } else $privacy = 0;

            if ($linkModel->isUnique($userId, $_POST["linkUrl"])) {
                $linkInfo = array(
                    "userId" => $_POST["userId"],
                    "title" => $_POST["linkTitle"],
                    "url" => $_POST["linkUrl"],
                    "description" => $_POST["linkDescription"],
                    "privacy" => $_POST["linkPrivacy"]
                );
                $linkModel->create($linkInfo);
                App::redirect("");
            }
            else {
                $linkInfo = array(
                    "title" => $_POST["linkTitle"],
                    "url" => $_POST["linkUrl"],
                    "description" => $_POST["linkDescription"],
                    "private" => $privacy);
                $data->setData("linkInfo", $linkInfo);
                $data->setData("errorMessage", "Link with such url already exists");
            }
        }
        $this->view = "LinkCreate";
        $this->renderView();
    }

    public function edit($linkId) {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
            $linkModel = new LinkModel();
            if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
                $userId = $_POST["userId"];
                if ($_POST["pastLinkUrl"] == $_POST["linkUrl"] || $linkModel->isUnique($userId, $_POST["linkUrl"])) {
                    $linkInfo = array(
                        "id" => $_POST["linkId"],
                        "title" => $_POST["linkTitle"],
                        "url" => $_POST["linkUrl"],
                        "description" => $_POST["linkDescription"],
                        "privacy" => $_POST["linkPrivacy"]
                    );
                    $linkModel->edit($linkInfo);
                    echo json_encode(array("edited"=>"ok", "message" => "Link edited successfully"));
                } else {
                    echo json_encode(array("edited"=>"not", "message" => "Link with such url already exists"));
                }
            }
        }
        else {
            $this->head = array("title" => "Edit link", "description" => "Link");
            $linkModel = new LinkModel();
            $data = ServiceProvider::getService("Data");
            if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
                $userId = $_POST["userId"];

                if ($_POST["pastLinkUrl"] == $_POST["linkUrl"] || $linkModel->isUnique($userId, $_POST["linkUrl"])) {
                    $linkInfo = array(
                        "id" => $_POST["linkId"],
                        "title" => $_POST["linkTitle"],
                        "url" => $_POST["linkUrl"],
                        "description" => $_POST["linkDescription"],
                        "privacy" => $_POST["linkPrivacy"]
                    );
                    $linkModel->edit($linkInfo);
                    App::redirect("link/read/" . $_POST["linkId"]);
                } else {
                    $linkInfo = array(
                        "linkId" => $_POST["linkId"],
                        "userId" => $_POST["userId"],
                        "title" => $_POST["linkTitle"],
                        "url" => $_POST["pastLinkUrl"],
                        "description" => $_POST["linkDescription"],
                        "private" => $_POST["linkPrivacy"]);
                    $data->setData("linkInfo", $linkInfo);
                    $data->setData("errorMessage", "Link with such url already exists");
                }
            }
            else {
                $link = $linkModel->get($linkId);
                $linkInfo = array(
                    "linkId" => $link["id"],
                    "userId" => $link["userId"],
                    "title" => $link["title"],
                    "url" => $link["url"],
                    "description" => $link["description"],
                    "private" => $link["private"]);
                $data->setData("linkInfo", $linkInfo);
            }
            $this->view = "LinkEdit";
            $this->renderView();
        }
    }
}