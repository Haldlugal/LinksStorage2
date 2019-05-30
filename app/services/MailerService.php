<?php
use PHPMailer\PHPMailer\PHPMailer;
require_once 'app/services/vendor/autoload.php';

class MailerService {

    private $mail;

    public function __construct($settings=array()) {
        $this->mail = new PHPMailer(TRUE);
        if (empty($settings)) {
            $config = new Config();
            $this->mail->setFrom($config->getEmailAddressFrom(), $config->getNameFrom());
            /* SMTP parameters. */
            /* Tells PHPMailer to use SMTP. */
            $this->mail->isSMTP();
            /* SMTP server address. */
            $this->mail->Host = $config->getSmtpServerAddress();
            /* Use SMTP authentication. */
            $this->mail->SMTPAuth = TRUE;
            /* Set the encryption system. */
            $this->mail->SMTPSecure = $config->getSmtpSecure();
            /* SMTP authentication username. */
            $this->mail->Username = $config->getSmtpAuthentificationName();
            /* SMTP authentication password. */
            $this->mail->Password = $config->getSmtpAuthentificationPassword();
            /* Set the SMTP port. */
            $this->mail->Port = $config->getSmtpPort();
            /*Add HTML to body*/
            $this->mail->isHTML(true);
        }
        else {
            $this->mail->setFrom($settings["emailAddressFrom"], $settings["nameFrom"]);
            /* SMTP parameters. */
            /* Tells PHPMailer to use SMTP. */
            $this->mail->isSMTP();
            /* SMTP server address. */
            $this->mail->Host = $settings["smtpAddress"];
            /* Use SMTP authentication. */
            $this->mail->SMTPAuth = TRUE;
            /* Set the encryption system. */
            $this->mail->SMTPSecure = $settings["smtpSecure"];
            /* SMTP authentication username. */
            $this->mail->Username = $settings["smtpAuthName"];
            /* SMTP authentication password. */
            $this->mail->Password = $settings["smtpAuthPassword"];
            /* Set the SMTP port. */
            $this->mail->Port = $settings["smtpPort"];
            /*Add HTML to body*/
            $this->mail->isHTML(true);
        }
    }

    public function sendRegistrationMail($email, $verificationText)
    {
        $this->mail->addAddress($email);
        $this->mail->Subject = "Account verification from Blog";
        $this->mail->Body = "<p>Thanks for signing up!</p>
        <p>Your account has been created. Please activate it using following link: <a href='http://linkstorage/verify/?verificationText=$verificationText'>http://linkstorage/verify/?verificationText=$verificationText</a></p>";
        try {
            $this->mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo $e->errorMessage();
        }
    }
}