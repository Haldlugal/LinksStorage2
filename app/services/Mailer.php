<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/services/vendor/autoload.php';

class Mailer {

    private $mail;

    public function __construct() {

        $this->mail = new PHPMailer(TRUE);
        try {
            $this->mail->setFrom(Config::getEmailAddressFrom(), Config::getNameFrom());

            /* SMTP parameters. */

            /* Tells PHPMailer to use SMTP. */
            $this->mail->isSMTP();

            /* SMTP server address. */
            $this->mail->Host = Config::getSmtpServerAddress();

            /* Use SMTP authentication. */
            $this->mail->SMTPAuth = TRUE;

            /* Set the encryption system. */
            $this->mail->SMTPSecure = Config::getSmtpSecure();

            /* SMTP authentication username. */
            $this->mail->Username = Config::getSmtpAuthentificationName();

            /* SMTP authentication password. */
            $this->mail->Password = Config::getSmtpAuthentificationPassword();

            /* Set the SMTP port. */
            $this->mail->Port = Config::getSmtpPort();
        } catch (Exception $e) {
            echo $e->errorMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function sendRegistrationMail($email, $verificationText)
    {
        $this->mail->addAddress($email);
        $this->mail->Subject = "Account verification from Blog";
        $this->mail->Body = "Thanks for signing up! Your account has been created. 
            Please activate it using following link: http://linkstorage/verify/$verificationText";
        try {
            $this->mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo $e->errorMessage();
        }
    }
}