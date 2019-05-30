<?php


class LoginController extends CommonController {

    private $data;

    public function __construct()
    {
        $this->data = ServiceProvider::getService("Data");
    }

    public function index()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $userModel = new UserModel();
            $user = $userModel->checkLogin($_POST["login"], $_POST["password"]);
            if ($user==0) {
                $this->data->setData("errorMessage", "Your Login Name or Password is invalid");
            }
            else if ($user["approved"]==0) {
                $this->data->setData("errorMessage", "Your account isn't activated yet");
                if ($user["verificationText"]=="") {
                    $this->data->setData("errorMessage", $this->data->getData()["errorMessage"]."<br>Do you want to <a href='/login/sendlink/".$user["id"]."'>resend</a> an activation link?");
                }
                else {
                    $this->data->setData("errorMessage", $this->data->getData()["errorMessage"]."<br>Please, check your email for activation link");
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

    function sendlink($userId)
    {
        $this->data = ServiceProvider::getService("Data");
        $userModel = new UserModel();
        $userInfo = $userModel->selectById($userId);
        if ($userInfo["approved"]==0 && $userInfo["verificationText"]=="") {
            $mailer = ServiceProvider::getService("Mailer");
            $verificationText = md5(rand(0, 10000));
            $userModel->updateVerificationText($userId, $verificationText);
            $mailer->sendRegistrationMail($userInfo["email"], $verificationText);
            $this->data->setData("successMessage", "Your activation link was resend to your email");
        }
        else if ($userInfo["approved"] != 0) {
            $this->data->setData("errorMessage", "Your account is already approved");
        }
        else if ($userInfo["verificationText"] != "") {
            $this->data->setData("errorMessage", "Please, check your email for activation link");
        }
        $this->head = array("title" => "Login page", "description" => "Login");
        $this->view = "Login";
        $this->renderView();
    }
}