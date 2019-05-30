<?php


class RightsService
{
    private $pdo;

    public function __construct() {
        $database = ServiceProvider::getService("Database");
        $this->pdo = $database->getConnection();
    }

    /*checks if userRole has particular permission from db */
    public function checkPermission($roleId, $target, $operation) {
        $selectRolePermissionsStatement = $this->pdo->prepare("SELECT * FROM rolePermissions WHERE roleId = :roleId AND target = :target AND operation = :operation");
        $roleData = array("roleId" => $roleId, "target" => $target, "operation" => $operation);
        $selectRolePermissionsStatement->execute($roleData);
        if ($selectRolePermissionsStatement->rowCount()>0){
            return true;
        }
        else {
            return false;
        }
    }
    /*returns access level for target and operation for role*/
    public function checkAccessLevel($roleId, $target, $operation) {
        $statement = $this->pdo->prepare("SELECT access_level FROM rolePermissions WHERE roleId = :roleId AND target = :target AND operation = :operation");
        $data = array("roleId" => $roleId, "target" => $target, "operation" => $operation);
        $statement->execute($data);
        $row = $statement->fetch();
        return $row["access_level"];
    }

    public function getRoles() {
        $statement = $this->pdo->prepare("SELECT * FROM roles");
        $statement->execute();
        return $statement->fetchAll();
    }


}