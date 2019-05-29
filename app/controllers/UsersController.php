<?php


class UsersController extends ElementsController
{

    function __invoke()
    {
        $userModel = new UserModel();
        $users = $userModel->selectList();
        $data = ServiceProvider::getService("Data");

        $pagination =  $this->getPagination($users);
        $usersToShow = $this->getElementsToShow($users);

        $data->setData("users", $usersToShow);
        $data->setData("pagination", $pagination);


        $this->head["title"] = "Users List";
        $this->view = "UsersList";
        $this->renderView();
    }
}