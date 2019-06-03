<?php


class LinkModel {

    private $pdo;

    public function __construct()
    {
        $database = ServiceProvider::getService("Database");
        $this->pdo = $database->getConnection();
    }

    public function get ($id) {
        $selectLinkStatement = $this->pdo->prepare("SELECT links.id, links.userId, links.title, links.description, links.url, links.private, links.dateCreated, users.firstName, users.lastName FROM links
            LEFT JOIN users ON links.userId = users.id 
            WHERE links.id = :linkId");
        $linkData = array("linkId"=>$id);
        $selectLinkStatement->execute($linkData);
        return $selectLinkStatement->fetch();
    }

    public function edit($linkInfo) {

        if ($linkInfo["privacy"]=="on") {
            $linkInfo["privacy"] = 1;
        }
        else {
            $linkInfo["privacy"] = 0;
        }
        $editLinkStatement = $this->pdo->prepare("UPDATE links SET title = :title, url = :url, description = :description, private = :private  WHERE id = :linkId");
        $linkData = array("linkId" => $linkInfo["id"], "title" => $linkInfo["title"], "url" => $linkInfo["url"], "description" => $linkInfo["description"], "private" => $linkInfo["privacy"]);
        $editLinkStatement->execute($linkData);
    }

    public function create($linkInfo) {
        if ($linkInfo["privacy"]=="on") {
            $linkInfo["privacy"] = 1;
        }
        else {
            $linkInfo["privacy"] = 0;
        }
        $createLinkStatement = $this->pdo->prepare("INSERT INTO links (userId, title, description, url, private) VALUES (:userId, :title, :description, :url, :private)");
        $data = array("userId"=>$linkInfo["userId"], "title" => $linkInfo["title"], "url" => $linkInfo["url"], "description" => $linkInfo["description"], "private" => $linkInfo["privacy"]);
        $createLinkStatement->execute($data);
    }

    public function delete($id) {
        $deleteLinkStatement = $this->pdo->prepare("DELETE FROM links WHERE id = :linkId");
        $linkData = array("linkId"=>$id);
        $deleteLinkStatement->execute($linkData);
    }

    public function getList() {
        $selectLinksStatement = $this->pdo->prepare("SELECT links.id, links.userId, links.title, links.description, links.url, links.private, links.dateCreated, users.firstName, users.lastName 
            FROM links
            LEFT JOIN users ON links.userId = users.id 
            ORDER BY dateCreated DESC");
        $selectLinksStatement->execute();
        return $selectLinksStatement->fetchAll();
    }

    public function getListByUserId($userId) {
        $selectLinksStatement = $this->pdo->prepare("SELECT links.id, links.userId, links.title, links.description, links.url, links.private, links.dateCreated, users.firstName, users.lastName 
            FROM links
            LEFT JOIN users ON links.userId = users.id
            WHERE userId = :userId 
            ORDER BY dateCreated DESC");
        $data = array("userId" => $userId);
        $selectLinksStatement->execute($data);
        return $selectLinksStatement->fetchAll();
    }

    public function getReadableList($links) {
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

    public function clearUsersLinks($userId) {
        $statement = $this->pdo->prepare("UPDATE links SET userId = 1, private = 0 WHERE userId = :userId");
        $data = array("userId" => $userId);
        $statement->execute($data);
    }
}