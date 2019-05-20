<?php


class UserModel
{
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
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

    public function selectUserById() {

    }

    public function selectUsersList() {

    }

    public function checkUser($login, $password) {
        $selectPasswordStatement = $this->pdo->prepare("SELECT id,password FROM users WHERE login = :login");
        $userData = array("login" => $login);
        $selectPasswordStatement->execute($userData);
        $row = $selectPasswordStatement->fetch();
        if (password_verify($password, $row["password"])) {
            return $row["id"];
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