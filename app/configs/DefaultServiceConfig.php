<?php


class DefaultServiceConfig {

    public function get() {
        return [
            "Config" => "Config",
            "Router" => "RouterService",
            "Database" => "PDOService",
            "Mailer" => "MailerService",
            "Authorization" => "AuthorizationService",
            "AccessControl" => "AccessControlService"
        ];
    }
}