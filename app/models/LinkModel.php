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

    public function getLinksListByUserId($userId) {
        $selectLinksStatement = $this->pdo->prepare("SELECT * FROM links WHERE userId = :userId");
        $data = array("userId" => $userId);
        $selectLinksStatement->execute($data);
        return $selectLinksStatement->fetchAll();
    }

    public function editLink($id, $title, $url, $description) {
        $editLinkStatement = $this->pdo->prepare("UPDATE links SET title = :title, url = :url, description = :description WHERE id = :linkId");
        $linkData = array("linkId" => $id, "title" => $title, "url" => $url, "description" => $description);
        $editLinkStatement->execute($linkData);

    }
}