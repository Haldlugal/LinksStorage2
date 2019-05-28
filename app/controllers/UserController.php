<?php


class UserController extends CommonController
{

    function process($params)
    {
        $userModel = new UserModel();
        $rightsModel = new RightsModel();
        $data = ServiceProvider::getService("Data");
        $authentication = ServiceProvider::getService("Authentication");

        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($params["operation"] == "edit") {
                if ($_POST["approved"] == "on") {
                    $approved = 1;
                } else $approved = 0;

                if (ServiceProvider::getService("Authentication")->canEditThisUser($_POST["userId"])) {
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $userModel->editUser($_POST["userId"], $_POST["login"], $_POST["firstName"], $_POST["lastName"], $password,
                        $_POST["email"], $_POST["roleId"], $approved );
                }
                if ($authentication->canEditOtherUsers()) {
                    App::redirect("users");
                }
                else {
                    App::redirect("");
                }

            }
        }

        if ($params["operation"] == "delete") {
            $linkModel = new LinkModel();
            $linkModel->clearUsersLinks($params["data"]);
            $userModel->deleteUser($params["data"]);
            App::redirect("users");
        }
        if (!ServiceProvider::getService("Authentication")->canEditThisUser($params["data"])) {
            App::redirect("error");
        }
        $user = $userModel->selectUserById($params["data"]);
        $roles = $rightsModel->getRoles();

        $data->setData("id", $user["id"]);
        $data->setData("login", $user["login"]);
        $data->setData("email", $user["email"]);
        $data->setData("firstName", $user["firstName"]);
        $data->setData("lastName", $user["lastName"]);
        $data->setData("roleId", $user["roleId"]);
        $data->setData("approved", $user["approved"]);
        $data->setData("roles", $roles);
        $data->setData("canEditOtherUsers", $authentication->canEditOtherUsers());

        $this->head = array("title" => "Edit user", "description" => "Edit User");
        $this->view = "UserEdit";
        $this->renderView();
    }
}