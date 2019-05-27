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
        $selectLinksStatement = $this->pdo->prepare("SELECT * FROM links ORDER BY dateCreated DESC");
        $selectLinksStatement->execute();
        return $selectLinksStatement->fetchAll();
    }

    public function getLinksListByUserId($userId) {
        $selectLinksStatement = $this->pdo->prepare("SELECT * FROM links WHERE userId = :userId");
        $data = array("userId" => $userId);
        $selectLinksStatement->execute($data);
        return $selectLinksStatement->fetchAll();
    }

    public function isLinkUnique($userId, $linkUrl) {
        $statement = $this->pdo->prepare("SELECT COUNT(id) FROM links WHERE userId = :userId AND url = :linkUrl");
        $data = array("userId" => $userId, "linkUrl" => $linkUrl);
        $statement->execute($data);
        if ($statement->fetchColumn()){
            return false;
        }
        else return true;
    }

    public function editLink($id, $title, $url, $description, $privacy) {
        if ($privacy!=0) {
            $privacy = 1;
        }
        $editLinkStatement = $this->pdo->prepare("UPDATE links SET title = :title, url = :url, description = :description, private = :private  WHERE id = :linkId");
        $linkData = array("linkId" => $id, "title" => $title, "url" => $url, "description" => $description, "private" => $privacy);
        $editLinkStatement->execute($linkData);
    }

    public function deleteLink($id) {
        $deleteLinkStatement = $this->pdo->prepare("DELETE FROM links WHERE id = :linkId");
        $linkData = array("linkId"=>$id);
        $deleteLinkStatement->execute($linkData);
    }

    public function createLink($userId, $title, $url, $description, $privacy) {
        $createLinkStatement = $this->pdo->prepare("INSERT INTO links (userId, title, description, url, private) VALUES (:userId, :title, :description, :url, :private)");
        $data = array("userId"=>$userId, "title" => $title, "url" => $url, "description" => $description, "private" => $privacy);
        $createLinkStatement->execute($data);
    }

    public function clearUsersLinks($userId) {
        $statement = $this->pdo->prepare("UPDATE links SET userId = 1 WHERE userId = :userId");
        $data = array("userId" => $userId);
        $statement->execute($data);
    }
}