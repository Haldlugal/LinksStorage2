<?php


class LinkController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Link page", "description" => "Link");
        $linkModel = new LinkModel();
        $userId = ServiceProvider::getService("Authorization")->getUserInfo()["id"];
        $data = ServiceProvider::getService("Data");

        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($params["operation"] == "edit") {
                if ($_POST["linkPrivacy"] == "on") {
                    $privacy = 1;
                } else $privacy = 0;
                if ($_POST["pastLinkUrl"]==$_POST["linkUrl"] || $linkModel->isLinkUnique($userId, $_POST["linkUrl"])) {
                    if (ServiceProvider::getService("Authentication")->canEditThisLink($_POST["linkId"])) {
                        $linkModel->editLink($_POST["linkId"], $_POST["linkTitle"], $_POST["linkUrl"], $_POST["linkDescription"], $privacy);
                    }
                    App::redirect("link/read/" . $_POST["linkId"]);
                }
                else {
                    $data->setData("title", $_POST["linkTitle"]);
                    $data->setData("url", $_POST["pastLinkUrl"]);
                    $data->setData("description", $_POST["linkDescription"]);
                    $data->setData("private", $privacy);
                    $data->setData("errorMessage", "Link with such url already exists");
                }

            }
            else if ($params["operation"] == "create") {
                if ($_POST["linkPrivacy"] == "on") {
                    $privacy = 1;
                } else $privacy = 0;

                if ($linkModel->isLinkUnique($userId, $_POST["linkUrl"])) {
                    $linkModel->createLink($_POST["userId"], $_POST["linkTitle"], $_POST["linkUrl"], $_POST["linkDescription"], $privacy);
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
        }
        else {
            if ($params["operation"] == "delete") {
                if (ServiceProvider::getService("Authentication")->canDeleteThisLink($params["data"])) {
                    $linkModel->deleteLink($params["data"]);
                }
                App::redirect("");
            }

            $link = $linkModel->getLink($params["data"]);
            $data->setData("title",$link["title"]);
            $data->setData("linkId",$link["id"]);
            $data->setData("description",$link["description"]);
            $data->setData("url",$link["url"]);
            $data->setData("private", $link["private"]);
        }

        if ($params["operation"] == "read") {
            $this->view = "LinkShow";
        }
        else if ($params["operation"] == "create") {
            $this->view = "LinkCreate";
        }
        else if ($params["operation"] == "edit") {
            $this->view = "LinkEdit";
        }

        $this->renderView();
    }
}