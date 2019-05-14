<?php


class LinkController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Link page", "description" => "Link");

        $linkModel = new LinkModel();
        $link = $linkModel->getLink($params[0]);
        var_dump($linkModel);
        $this->data["title"] = $link["title"];
        $this->data["description"] = $link["description"];
        $this->data["url"] = $link["url"];

        $this->view = "link";
    }
}