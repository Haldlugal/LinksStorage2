<?php


class UsersController extends CommonController
{

    function process($params)
    {
        $userModel = new UserModel();
        $users = $userModel->selectUsersList();
        $data = ServiceProvider::getService("Data");
        $page = $_GET["page"];
        $elementsOnPage = $_GET["elementsOnPage"];

        if ($page == "") {
            $page = 1;
        }
        if ($elementsOnPage == "") {
            $elementsOnPage = ServiceProvider::getService("Config")->getPaginationCount();
        }


        $usersToShow = array_slice($users, ($page-1)*$elementsOnPage, $elementsOnPage);
        $pagination = ServiceProvider::getService("Pagination")->generatePagination(count($users), $elementsOnPage, $page);

        $data->setData("users", $usersToShow);
        $data->setData("pagination", $pagination);


        $this->head["title"] = "Users List";
        $this->view = "UsersList";
        $this->renderView();
    }
}