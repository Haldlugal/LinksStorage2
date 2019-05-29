<?php

class RegisterController extends CommonController {

    private $data;
    private $userModel;

    public function __construct()
    {
        $this->data = ServiceProvider::getService("Data");
        $this->userModel = new UserModel();
    }

    function __invoke() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->userModel = new UserModel();

            if ($this->userModel->loginExists($_POST["login"])){
                $this->data->setData("errorMessage", "User with such login: ".$_POST["login"]." already exists");
            }
            else {
                $this->registerUser();
                $this->data->setData("successMessage", "Confirmation email has been sent to you");
            }
        }
        $this->head = array("title" => "Register page", "description" => "Register");
        $this->view = "Register";
        $this->renderView();
    }

    private function registerUser() {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $verificationText = md5(rand(0, 10000));
        $userData = array("login" => $_POST["login"],
            "firstName" => $_POST["firstName"],
            "lastName" => $_POST["lastName"],
            "email" => $_POST["email"],
            "password" => $password,
            "verificationText" => $verificationText);
        $this->userModel->create($userData);
        ServiceProvider::getService("Mailer")->sendRegistrationMail($_POST["email"], $verificationText);
    }
}

