<?php


class LoginController extends CommonController {

    function process($params)
    {
        $data = ServiceProvider::getService("Data");
        if ($params["operation"] == "sendlink") {
            $userModel = new UserModel();
            $userInfo = $userModel->selectUserById($params["data"]);
            if ($userInfo["approved"]==0 && $userInfo["verificationText"]=="") {
                $mailer = ServiceProvider::getService("Mailer");
                $verificationText = md5(rand(0, 10000));
                $userModel->updateVerificationText($params["data"], $verificationText);
                $mailer->sendRegistrationMail($userInfo["email"], $verificationText);
                $data->setData("successMessage", "Your activation link was resend to your email");
            }
            else if ($userInfo["approved"] != 0) {
                $data->setData("errorMessage", "Your email is already approved");
            }
            else if ($userInfo["verificationText"] != "") {
                $data->setData("errorMessage", "Please, check your email for activation link");
            }

        }
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $userModel = new UserModel();
            $user = $userModel->checkUser($_POST["login"], $_POST["password"]);
            if ($user==0) {
                $data->setData("errorMessage", "Your Login Name or Password is invalid");
            }
            else if ($user["approved"]==0) {
                $data->setData("errorMessage", "Your account isn't activated yet");
                if ($user["verificationText"]=="") {
                    $data->setData("errorMessage", $data->getData()["errorMessage"]."<br>Do you want to <a href='/login/sendlink/".$user["id"]."'>resend</a> an activation link?");
                }
                else {
                    $data->setData("errorMessage", $data->getData()["errorMessage"]."<br>Please, check your email for activation link");
                }
            }
            else {
                $_SESSION['userId'] = $user['id'];
                App::redirect("");
            }

        }

        $this->head = array("title" => "Login page", "description" => "Login");
        $this->view = "Login";
        $this->renderView();
    }
}