<?php


class DefaultServiceConfig {

    public function get() {
        return [
            "Router" => "RouterService",
            "Database" => "PDOService",
            "Mailer" => "MailerService",
            "Authorization" => "AuthorizationService",
            "Authentication" => "AuthenticationService",
            "AccessControl" => "AccessControlService",
            "Config" => "Config",
            "Data" => "DataService",
            "Pagination" => "PaginationService"
        ];
    }
}