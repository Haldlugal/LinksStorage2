<?php


class UserController extends ElementsController
{
    private $data;
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->data = ServiceProvider::getService("Data");
    }

    function index()    {

        $users = $this->userModel->selectList();

        $pagination =  $this->getPagination($users);
        $usersToShow = $this->getElementsToShow($users);

        $this->data->setData("users", $usersToShow);
        $this->data->setData("pagination", $pagination);

        $this->head["title"] = "Users List";
        $this->view = "UsersList";
        $this->renderView();
    }

    public function edit($userId) {
        if ($_SERVER["REQUEST_METHOD"]=="POST" ) {
            if ($_POST["pastUserLogin"] == $_POST["login"]) {
                $this->userModel->edit(array("userId" => $_POST["userId"],
                    "login" => $_POST["login"],
                    "firstName" => $_POST["firstName"],
                    "lastName" => $_POST["lastName"],
                    "password" => $_POST["password"],
                    "email" => $_POST["email"],
                    "roleId" => $_POST["roleId"],
                    "approved" => $_POST["approved"]));
                $this->data->setData("success", "User edited successfully");
            }
        }
        $user = $this->userModel->selectById($userId);
        $rightsModel = new RightsService();
        $roles = $rightsModel->getRoles();
        $this->data->setData("id", $user["id"]);
        $this->data->setData("login", $user["login"]);
        $this->data->setData("email", $user["email"]);
        $this->data->setData("firstName", $user["firstName"]);
        $this->data->setData("lastName", $user["lastName"]);
        $this->data->setData("roleId", $user["roleId"]);
        $this->data->setData("approved", $user["approved"]);
        $this->data->setData("roles", $roles);

        $this->head = array("title" => "Edit user", "description" => "Edit User");
        $this->view = "UserEdit";
        $this->renderView();
    }

    public function delete($userId) {
        $linkModel = new LinkModel();
        $linkModel->clearUsersLinks($userId);
        $this->userModel->delete($userId);
        App::redirect("users");
    }
}