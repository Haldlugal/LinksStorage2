<?php


class Config
{
    /*email configuration*/
    private static $emailAddressFrom = "test@linkstorage.com";
    private static $nameFrom = "Linkstorage";
    private static $smtpServerAddress = "smtp.gmail.com";
    private static $smtpAuthentificationName = "haldlugaltest@gmail.com";
    private static $smtpAuthentificationPassword = "R00twala";
    private static $smtpSecure = "tls";
    private static $smtpPort = 587;

    /*database configuration*/
    private static $dbHost = "localhost";
    private static $dbLogin = "root";
    private static $dbPassword = "R00twala";
    private static $dbName = "linkstorage";

    /**
     * @return string
     */
    public static function getEmailAddressFrom()
    {
        return self::$emailAddressFrom;
    }

    /**
     * @return string
     */
    public static function getNameFrom()
    {
        return self::$nameFrom;
    }

    /**
     * @return string
     */
    public static function getSmtpServerAddress()
    {
        return self::$smtpServerAddress;
    }

    /**
     * @return string
     */
    public static function getSmtpAuthentificationName()
    {
        return self::$smtpAuthentificationName;
    }

    /**
     * @return string
     */
    public static function getSmtpAuthentificationPassword()
    {
        return self::$smtpAuthentificationPassword;
    }

    /**
     * @return string
     */
    public static function getDbHost()
    {
        return self::$dbHost;
    }

    /**
     * @return string
     */
    public static function getDbLogin()
    {
        return self::$dbLogin;
    }

    /**
     * @return string
     */
    public static function getDbPassword()
    {
        return self::$dbPassword;
    }

    /**
     * @return string
     */
    public static function getDbName()
    {
        return self::$dbName;
    }

    /**
     * @return string
     */
    public static function getSmtpSecure()
    {
        return self::$smtpSecure;
    }

    /**
     * @return string
     */
    public static function getSmtpPort()
    {
        return self::$smtpPort;
    }

}