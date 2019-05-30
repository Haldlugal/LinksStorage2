<?php


class Config
{
    /*email configuration*/
    private $emailAddressFrom = "test@linkstorage.com";
    private $nameFrom = "Linkstorage";
    private $smtpServerAddress = "smtp.gmail.com";
    private $smtpAuthentificationName = "haldlugaltest@gmail.com";
    private $smtpAuthentificationPassword = "R00twala";
    private $smtpSecure = "tls";
    private $smtpPort = 587;

    /*database configuration*/
    private $dbHost = "localhost";
    private $dbLogin = "root";
    private $dbPassword = "R00twala";
    private $dbName = "linkstorage";

    /*clear user links*/
    private $userActivationLinkClearTime = 5;

    /*policies registered by default*/
    private $policies = array("LinkPolicy");

    /*routing*/
    private $routing = array(
        "" => array("controllerClass"=>"Link", "action" => "index"),
        "ShowMy" => array("controllerClass" => "Link", "action" => "showMy"),
        "Users" => array("controllerClass" => "User", "action" => "index"),
        "Error404" => array("controllerClass" => "Error", "action" => "error404"),
        "Error403" => array("controllerClass" => "Error", "action" => "error403")
    );

    /**
     * @return array
     */
    public function getPolicies()
    {
        return $this->policies;
    }

    /*default pagination*/
    private $paginationCount = 5;
    /**
     * @return string
     */
    public function getEmailAddressFrom()
    {
        return $this->emailAddressFrom;
    }

    /**
     * @return string
     */
    public function getNameFrom()
    {
        return $this->nameFrom;
    }

    /**
     * @return string
     */
    public function getSmtpServerAddress()
    {
        return $this->smtpServerAddress;
    }

    /**
     * @return string
     */
    public function getSmtpAuthentificationName()
    {
        return $this->smtpAuthentificationName;
    }

    /**
     * @return string
     */
    public function getSmtpAuthentificationPassword()
    {
        return $this->smtpAuthentificationPassword;
    }

    /**
     * @return string
     */
    public function getSmtpSecure()
    {
        return $this->smtpSecure;
    }

    /**
     * @return int
     */
    public function getSmtpPort()
    {
        return $this->smtpPort;
    }

    /**
     * @return string
     */
    public function getDbHost()
    {
        return $this->dbHost;
    }

    /**
     * @return string
     */
    public function getDbLogin()
    {
        return $this->dbLogin;
    }

    /**
     * @return string
     */
    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @return int
     */
    public function getUserActivationLinkClearTime()
    {
        return $this->userActivationLinkClearTime;
    }

    /**
     * @return int
     */
    public function getPaginationCount()
    {
        return $this->paginationCount;
    }

    /**
     * @return array
     */
    public function getRouting()
    {
        return $this->routing;
    }


}