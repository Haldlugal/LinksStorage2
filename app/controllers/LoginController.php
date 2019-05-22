<?php


class LoginController extends CommonController {

    function process($params)
    {

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $userModel = new UserModel();
            $user = $userModel->checkUser($_POST["login"], $_POST["password"]);
            if ($user==0) {
                $this->data["errorMessage"] = "Your Login Name or Password is invalid";
            }
            else if ($user["approved"]==0) {
                $this->data["errorMessage"] = "Your account isn't approved yet";
            }
            else {
                $_SESSION['userId'] = $user['id'];
                App::redirect("main-page");
            }

        }

        $this->head = array("title" => "Login page", "description" => "Login");
        $this->view = "login";
        $this->renderView();
    }
}