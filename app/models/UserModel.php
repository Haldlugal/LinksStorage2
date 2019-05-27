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

    public function deleteUser($userId) {
        $statement = $this->pdo->prepare("DELETE FROM users WHERE id = :userId");
        $data = array("userId" => $userId);
        $statement->execute($data);
    }

    public function editUser($userId, $login, $firstName, $lastName, $password, $email, $roleId, $approved) {
        if ($password == "") {
            $statement = $this->pdo->prepare("UPDATE users SET login = :login, firstName = :firstName, lastName = :lastName, email = :email, roleId = :roleId, approved = :approved WHERE id = :userId");
            $data = array("userId" => $userId, "login" => $login, "firstName" => $firstName, "lastName" => $lastName, "email" => $email,
                "roleId" => $roleId, "approved" => $approved);
        }
        else {
            $statement = $this->pdo->prepare("UPDATE users SET login = :login, firstName = :firstName, lastName = :lastName, email = :email,
            password = :password, roleId = :roleId, approved = :approved WHERE id = :userId");
            $data = array("userId" => $userId, "login" => $login, "firstName" => $firstName, "lastName" => $lastName, "email" => $email,
                "password" => $password, "roleId" => $roleId, "approved" => $approved);
        }

        $statement->execute($data);
    }

    public function loginExists($login) {
        $statement = $this->pdo->prepare("SELECT COUNT(id) FROM users WHERE login = :login");
        $data = array("login" => $login);
        $statement->execute($data);
        if ($statement->fetchColumn()){
            return true;
        }
        else return false;
    }
    public function selectUserById($userId) {
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE id = :userId");
        $userData = array("userId" => $userId);
        $statement->execute($userData);
        $row = $statement->fetch();
        return $row;
    }

    public function selectUsersList() {
        $statement = $this->pdo->prepare("SELECT * FROM users");
        $statement->execute();
        return $statement->fetchAll();
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

    public function emailExists($email) {
        $countMailStatement = $this->pdo->prepare("SELECT COUNT(login) FROM users WHERE email = :email");
        $userData = array("email" => $email);
        $countMailStatement->execute($userData);
        if ($countMailStatement->fetchColumn()) {
            return true;
        }
        else return false;
    }

    /*Activates user's account by verification text*/
    public function verifyUser ($verificationText) {
        $approvementStatement = $this->pdo->prepare("UPDATE users SET approved = 1 WHERE verificationText = :verificationText");
        $data = array("verificationText" => $verificationText);
        $approvementStatement->execute($data);
        return $approvementStatement->rowCount();
    }

    public function updateVerificationText($userId, $verificationText) {
        $updateStatement = $this->pdo->prepare("UPDATE users SET verificationText = :verificationText WHERE id = :id");
        $data = array("verificationText" => $verificationText, "id" => $userId);
        $updateStatement->execute($data);
    }

    public function searchForVerificationLinks() {
        $searchStatement = $this->pdo->prepare("SELECT verificationText, verificationCreated FROM users WHERE verificationText != ''");
        $searchStatement->execute();
        return $searchStatement->fetchAll();
    }

    public function checkIfLinkExpired($link) {
        $searchStatement = $this->pdo->prepare("SELECT verificationCreated FROM users WHERE verificationText = :verificationText");
        $data = array("verificationText" => $link);
        $searchStatement->execute($data);
        $link = $searchStatement->fetch();
        $config = ServiceProvider::getService("Config");
        $clearTime = $config->getUserActivationLinkClearTime();
        $now = strtotime("-$clearTime minutes");
        if ($now >strtotime($link["verificationCreated"])){
            return true;
        }
        else return false;
    }
    public function deleteExpiredLink($link) {
        $deleteLinkStatement = $this->pdo->prepare("UPDATE users SET verificationText = '' WHERE verificationText = :verificationText");
        $data = array("verificationText" => $link);
        $deleteLinkStatement->execute($data);
    }


}