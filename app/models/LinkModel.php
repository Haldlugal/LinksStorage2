<?php


class LinkModel {

    private $pdo;

    public function __construct()
    {
        $database = ServiceProvider::getService("Database");
        $this->pdo = $database->getConnection();

    }

    public function getLink ($id) {
        $selectLinkStatement = $this->pdo->prepare("SELECT * FROM links WHERE id = :linkId");
        $linkData = array("linkId"=>$id);
        $selectLinkStatement->execute($linkData);
        return $selectLinkStatement->fetch();
    }

    public function getLinksList() {
        $selectLinksStatement = $this->pdo->prepare("SELECT * FROM links");
        $selectLinksStatement->execute();
        return $selectLinksStatement->fetchAll();
    }
}