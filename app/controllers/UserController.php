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
                if ($this->userModel->emailExists($_POST["email"])&&($_POST["email"]!=$_POST["pastEmail"])){
                    $this->data->setData("error", "User with such email: ". $_POST['email']." already exists");
                }
                else {
                    $userInfo = array("userId" => $_POST["userId"],
                        "login" => $_POST["login"],
                        "firstName" => $_POST["firstName"],
                        "lastName" => $_POST["lastName"],
                        "password" => $_POST["password"],
                        "email" => $_POST["email"],
                        "roleId" => $_POST["roleId"],
                        "approved" => $_POST["approved"]);
                    $this->userModel->edit($userInfo);
                    $this->data->setData("success", "User edited successfully");
                }
            }
        }
        $user = $this->userModel->selectById($userId);
        $rightsModel = new RightsService();
        $roles = $rightsModel->getRoles();
        $userInfoToShow = array(
            "id" => $user["id"],
            "login" => $user["login"],
            "email" => $user["email"],
            "firstName" => $user["firstName"],
            "lastName" => $user["lastName"],
            "roleId" => $user["roleId"],
            "approved" => $user["approved"],
        );
        $this->data->setData("userInfo", $userInfoToShow);
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