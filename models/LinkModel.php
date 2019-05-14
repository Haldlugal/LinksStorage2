<?php


class LinkModel {

    public function getLink ($id) {
        $pdo = Database::getConnection();
        $selectLinkStatement = $pdo->prepare("SELECT * FROM links WHERE id = :linkId");
        $linkData = array("linkId"=>$id);
        $selectLinkStatement->execute($linkData);
        return $selectLinkStatement->fetch();
    }

    public function getLinksList() {
        $pdo = Database::getConnection();
        $selectLinksStatement = $pdo->prepare("SELECT * FROM links");
        $selectLinksStatement->execute();
        return $selectLinksStatement->fetchAll();
    }
}