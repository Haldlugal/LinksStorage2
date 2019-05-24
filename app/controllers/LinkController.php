<?php


class LinkController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Link page", "description" => "Link");
        $linkModel = new LinkModel();
        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($params["operation"] == "edit") {
                if ($_POST["linkPrivacy"] == "on") {
                    $privacy = 1;
                } else $privacy = 0;
                if (ServiceProvider::getService("Authentication")->canEditThisLink($_POST["linkId"])) {
                    $linkModel->editLink($_POST["linkId"], $_POST["linkTitle"], $_POST["linkUrl"], $_POST["linkDescription"], $privacy);
                }

                App::redirect("link/read/" . $_POST["linkId"]);
            }
            if ($params["operation"] == "create") {
                if ($_POST["linkPrivacy"] == "on") {
                    $privacy = 1;
                } else $privacy = 0;
                $linkModel->createLink($_POST["userId"], $_POST["linkTitle"], $_POST["linkUrl"], $_POST["linkDescription"], $privacy);
                App::redirect("");
            }
        }




        if ($params["operation"] == "delete") {
            if (ServiceProvider::getService("Authentication")->canDeleteThisLink($params["data"])) {
                $linkModel->deleteLink($params["data"]);
            }
            App::redirect("");
        }
        $data = ServiceProvider::getService("Data");
        $link = $linkModel->getLink($params["data"]);

        $data->setData("title",$link["title"]);
        $data->setData("linkId",$link["id"]);
        $data->setData("description",$link["description"]);
        $data->setData("url",$link["url"]);
        $data->setData("private", $link["private"]);

        if ($params["operation"] == "read") {
            $this->view = "LinkShow";
        }
        else if ($params["operation"] == "edit"){
            $this->view = "LinkEdit";
        }
        else if ($params["operation"] == "create") {
            $this->view = "LinkCreate";
        }

        $this->renderView();
    }
}