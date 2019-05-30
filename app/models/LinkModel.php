<?php


class LinkModel {

    private $pdo;

    public function __construct()
    {
        $database = ServiceProvider::getService("Database");
        $this->pdo = $database->getConnection();
    }

    public function get ($id) {
        $selectLinkStatement = $this->pdo->prepare("SELECT * FROM links WHERE id = :linkId");
        $linkData = array("linkId"=>$id);
        $selectLinkStatement->execute($linkData);
        return $selectLinkStatement->fetch();
    }

    public function edit($id, $title, $url, $description, $privacy) {
        if ($privacy=="on") {
            $privacy = 1;
        }
        else {
            $privacy = 0;
        }
        $editLinkStatement = $this->pdo->prepare("UPDATE links SET title = :title, url = :url, description = :description, private = :private  WHERE id = :linkId");
        $linkData = array("linkId" => $id, "title" => $title, "url" => $url, "description" => $description, "private" => $privacy);
        $editLinkStatement->execute($linkData);
    }

    public function create($userId, $title, $url, $description, $privacy) {
        if ($privacy=="on") {
            $privacy = 1;
        }
        else {
            $privacy = 0;
        }
        $createLinkStatement = $this->pdo->prepare("INSERT INTO links (userId, title, description, url, private) VALUES (:userId, :title, :description, :url, :private)");
        $data = array("userId"=>$userId, "title" => $title, "url" => $url, "description" => $description, "private" => $privacy);
        $createLinkStatement->execute($data);
    }

    public function delete($id) {
        $deleteLinkStatement = $this->pdo->prepare("DELETE FROM links WHERE id = :linkId");
        $linkData = array("linkId"=>$id);
        $deleteLinkStatement->execute($linkData);
    }

    public function getList() {
        $selectLinksStatement = $this->pdo->prepare("SELECT * FROM links ORDER BY dateCreated DESC");
        $selectLinksStatement->execute();
        return $selectLinksStatement->fetchAll();
    }

    public function getListByUserId($userId) {
        $selectLinksStatement = $this->pdo->prepare("SELECT * FROM links WHERE userId = :userId ORDER BY dateCreated DESC");
        $data = array("userId" => $userId);
        $selectLinksStatement->execute($data);
        return $selectLinksStatement->fetchAll();
    }

    public function getReadableList() {
        $links = $this->getList();
        $linksToShow = array();
        foreach($links as $link) {
            if (ServiceProvider::getService("AccessControl")->checkRights("link", "read", $link["id"])) {
                array_push($linksToShow, $link);
            }
        }
        return $linksToShow;
    }

    public function isUnique($userId, $linkUrl) {
        $statement = $this->pdo->prepare("SELECT COUNT(id) FROM links WHERE userId = :userId AND url = :linkUrl");
        $data = array("userId" => $userId, "linkUrl" => $linkUrl);
        $statement->execute($data);
        if ($statement->fetchColumn()){
            return false;
        }
        else return true;
    }

    public function isPrivate($linkId) {
        $selectLinkStatement = $this->pdo->prepare("SELECT private FROM links WHERE id = :linkId");
        $linkData = array("linkId"=>$linkId);
        $selectLinkStatement->execute($linkData);
        $selectLinkStatement->fetchColumn();
    }

    public function clearUsersLinks($userId) {
        $statement = $this->pdo->prepare("UPDATE links SET userId = 1 WHERE userId = :userId");
        $data = array("userId" => $userId);
        $statement->execute($data);
    }
}