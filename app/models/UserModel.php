<?php


class UserModel
{
    private $pdo;

    public function __construct() {
        $database = ServiceProvider::getService("Database");
        $this->pdo = $database->getConnection();
    }

    public function addUser($userData) {
        $statement = $this->pdo->prepare("INSERT INTO users (login, firstName, lastName, password, email, verificationText)
                VALUES (:login, :firstName,:lastName, :password, :email, :verificationText)");
        $statement->execute($userData);
    }

    public function deleteUser() {

    }

    public function editUser() {

    }

    public function selectUserById($userId) {
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE id = :userId");
        $userData = array("userId" => $userId);
        $statement->execute($userData);
        $row = $statement->fetch();
        return $row;
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

    public function selectUsersList() {

    }

    public function checkUser($login, $password) {
        $selectPasswordStatement = $this->pdo->prepare("SELECT * FROM users WHERE login = :login");
        $userData = array("login" => $login);
        $selectPasswordStatement->execute($userData);
        $row = $selectPasswordStatement->fetch();
        if (password_verify($password, $row["password"])) {
            return $row;
        }
        else return 0;
    }

    public function loginExists($login) {
        $countLoginStatement = $this->pdo->prepare("SELECT COUNT(login) FROM users WHERE login = :login");
        $userData = array("login" => $login);
        $countLoginStatement->execute($userData);
        if ($countLoginStatement->fetchColumn()) {
            return true;
        }
        else return false;
    }

    public function verifyUser ($verificationText) {
        $aprovementStatement = $this->pdo->prepare("UPDATE users SET approved = 1 WHERE verificationText = :verificationText");
        $data = array("verificationText" => $verificationText);
        $aprovementStatement->execute($data);
        return $aprovementStatement->rowCount();
    }


}