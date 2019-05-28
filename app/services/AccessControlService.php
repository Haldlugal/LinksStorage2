<?php


class AccessControlService
{
    public function checkUserRights($userId, $controller, $operation){

        $rightsModel = new RightsModel();

        return $rightsModel->userPermitted($userId, $controller, $operation);

    }

}