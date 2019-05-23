<?php

class RegisterController extends CommonController {

    function process($params) {
        $this->data["error"] = "";
        $this->data["success"] = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userModel = new UserModel();

            if ($userModel->loginExists($_POST["login"])){
                $this->data["error"].= "User with such login: ".$_POST["login"]." already exists";
            }
            /*else if ($userModel->emailExists($_POST['email'])) {
                $this->data["error"].= "User with such email: ".$_POST["email"]." already exists";
            }*/
            else {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $verificationText = md5(rand(0, 10000));
                $userData = array("login" => $_POST["login"],
                    "firstName" => $_POST["firstName"],
                    "lastName" => $_POST["lastName"],
                    "email" => $_POST["email"],
                    "password" => $password,
                    "verificationText" => $verificationText);
                $userModel->addUser($userData);
                ServiceProvider::getService("Mailer")->sendRegistrationMail($_POST["email"], $verificationText);
                $this->data["success"].="Confirmation email has been sent to you";
            }
        }

        $this->head = array("title" => "Register page", "description" => "Register");

        $this->view = "Register";
        $this->renderView();
    }
}

