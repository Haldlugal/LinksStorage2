<?php


class LinkController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Link page", "description" => "Link");
        $linkModel = new LinkModel();
        if ($_SERVER["REQUEST_METHOD"]=="POST") {

        }

        $link = $linkModel->getLink($params["data"]);
        $this->data["id"] = $link["id"];
        $this->data["title"] = $link["title"];
        $this->data["description"] = $link["description"];
        $this->data["url"] = $link["url"];
        if ($params["operation"] == "read") {
            $this->view = "LinkShow";
        }
        else if ($params["operation"] == "edit"){
            $this->view = "LinkEdit";
        }

        $this->renderView();
    }
}