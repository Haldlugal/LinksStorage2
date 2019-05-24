<?php


class RightsModel
{
    private $pdo;

    public function __construct() {
        $database = ServiceProvider::getService("Database");
        $this->pdo = $database->getConnection();
    }

    public function selectUserPermissionsById($userId) {
        if ($userId != 0) {
            $selectUserStatement = $this->pdo->prepare("SELECT roleId FROM users WHERE id = :userId");
            $userData = array("userId" => $userId);
            $selectUserStatement->execute($userData);
            $row = $selectUserStatement->fetch();
            $roleId = $row["roleId"];
        }
        else {
            $roleId = 4;
        }
        $selectRolePermissionsStatement = $this->pdo->prepare("SELECT * FROM rolePermissions WHERE roleId = :roleId");
        $roleData = array("roleId" => $roleId);
        $selectRolePermissionsStatement->execute($roleData);
        $row = $selectRolePermissionsStatement->fetchAll();
        return $row;
    }

    public function userPermittedToReadPrivateLinks($roleId) {
        $statement = $this->pdo->prepare("SELECT COUNT(id) FROM rolePermissions WHERE roleId = :roleId AND name = 'showPrivateLinks'");
        $data = array("roleId" => $roleId);
        $statement->execute($data);
        if ($statement->fetchColumn()){
            return true;
        }
        else return false;
    }

    public function userPermittedToEditLink($roleId) {
        $statement = $this->pdo->prepare("SELECT * FROM rolePermissions WHERE roleId = :roleId AND name LIKE 'editLinks%'");
        $data = array("roleId" => $roleId);
        $statement->execute($data);
        $row = $statement->fetch();
        $permission = explode("::", $row["name"]);
        if ($permission[0]=="") {
            return "notPermitted";
        }
        else if($permission[1]=="own") {
            return "own";
        }
        else {
            return "any";
        }
    }

    public function userPermittedToDeleteLink($roleId) {
        $statement = $this->pdo->prepare("SELECT * FROM rolePermissions WHERE roleId = :roleId AND name LIKE 'deleteLinks%'");
        $data = array("roleId" => $roleId);
        $statement->execute($data);
        $row = $statement->fetch();
        $permission = explode("::", $row["name"]);
        if ($permission[0]=="") {
            return "notPermitted";
        }
        else if($permission[1]=="own") {
            return "own";
        }
        else {
            return "any";
        }
    }

    public function userPermittedToReadUsersList($roleId) {
        $statement = $this->pdo->prepare("SELECT COUNT(id) FROM rolePermissions WHERE roleId = :roleId AND name = 'showUsersList'");
        $data = array("roleId" => $roleId);
        $statement->execute($data);
        if ($statement->fetchColumn()){
            return true;
        }
        else return false;
    }




    public function userPermitted($userId, $controller, $operation) {
        if (is_null($operation)) {
            $operation = "";
        }
        $checkGeneralAccessStatement = $this->pdo->prepare("SELECT * FROM generalAccessPermissions WHERE controller = :controller");
        $controllerData = array("controller" => $controller);
        $checkGeneralAccessStatement->execute($controllerData);
        if ($checkGeneralAccessStatement->rowCount()>0) {
            return true;
        }
        if ($userId !=0) {
            $selectUserStatement = $this->pdo->prepare("SELECT roleId FROM users WHERE id = :userId");
            $userData = array("userId" => $userId);
            $selectUserStatement->execute($userData);
            $row = $selectUserStatement->fetch();
            $roleId = $row["roleId"];
        }
        else {
            $roleId = 4;
        }
        $selectRolePermissionsStatement = $this->pdo->prepare("SELECT * FROM rolePermissions WHERE roleId = :roleId AND controller = :controller AND operation = :operation");
        $roleData = array("roleId" => $roleId, "controller" => $controller, "operation" => $operation);
        $selectRolePermissionsStatement->execute($roleData);
        return $selectRolePermissionsStatement->rowCount()>0;
    }

    public function selectUserPermissionsByIdControllerOperation($userId, $controller, $operation="") {
        if ($userId !=0) {
            $selectUserStatement = $this->pdo->prepare("SELECT roleId FROM users WHERE id = :userId");
            $userData = array("userId" => $userId);
            $selectUserStatement->execute($userData);
            $row = $selectUserStatement->fetch();
            $roleId = $row["roleId"];
        }
        else {
            $roleId = 4;
        }
        $selectRolePermissionsStatement = $this->pdo->prepare("SELECT * FROM rolePermissions WHERE roleId = :roleId AND controller = :controller AND operation = :operation");
        $roleData = array("roleId" => $roleId, "controller" => $controller, "operation" => $operation);
        $selectRolePermissionsStatement->execute($roleData);
        $row = $selectRolePermissionsStatement->fetchAll();
        return $row;
    }
}