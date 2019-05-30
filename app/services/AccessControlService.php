<?php


class AccessControlService
{
    public function checkRights ($target, $action = "index", $data = "") {

        $target = ucwords($target);

        if (in_array($target."Policy", ServiceProvider::getService("Config")->getPolicies()) && file_exists("app/policies/$target"."Policy.php")) {
            return $this->checkPolicy($target, $action, $data);
        }
        else {

            return $this->checkRolePermissions($target, $action);
        }
    }

    private function checkPolicy($target, $action, $data) {
        $policyName = $target."Policy";
        $policy = new $policyName;

        if (method_exists($policy, $action)) {
            return call_user_func(array($policy, $action), $data);
        }
        else {
            return $this->checkRolePermissions($target, $action);
        }
    }

    private function checkRolePermissions($target, $action) {
        $userInfo = ServiceProvider::getService("Authentication")->getUserInfo();
        $rights = ServiceProvider::getService("Rights");
        return $rights->checkPermission($userInfo["roleId"], $target, $action);
    }

    //TODO policy registering (policy has priority around db rights, so we should have option to register/unregister it)

}