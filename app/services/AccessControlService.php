<?php


class AccessControlService
{
    public function checkUserRights($userId, $controller, $operation){
        $userModel = new UserModel();
        $controller = substr($controller, 0, -10);
        if (!$userModel->userPermitted($userId, $controller, $operation)) {
            App::redirect("error");
        }
    }

    public function checkUserByPrivacy($userId, $linkId) {
        /*Here i want to check users rights to access to private link*/
        /*check if users role link access is nonPrivate. If it is then we check if it is users link. */
    }
}