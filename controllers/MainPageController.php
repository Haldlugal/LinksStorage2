<?php


class MainPageController extends CommonController {

    function process($params)
    {
        $this->head = array("title" => "Main page", "description" => "Main");

        $linkModel = new LinkModel();
        $links = $linkModel->getLinksList();

        $this->data["links"] = $links;

        $this->view = "main";
    }
}