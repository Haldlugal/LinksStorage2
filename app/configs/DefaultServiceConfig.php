<?php


class DefaultServiceConfig {

    public function get() {
        return [
            "Router" => "RouterService",
            "Database" => "PDOService",
            "Mailer" => "MailerService",
            "Authentication" => "AuthenticationService",
            "AccessControl" => "AccessControlService",
            "Config" => "Config",
            "Data" => "DataService",
            "Pagination" => "PaginationService",
            "Rights" => "RightsService"
        ];
    }
}