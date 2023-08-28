<?php

namespace App\Libs\models;

use App\Models\System;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email
{
    private PHPMailer $mail;

    function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host = System::getSystem("smtp", "host", "smtp.qq.com");                     //Set the SMTP server to send through
        $this->mail->SMTPAuth = System::getSystem("smtp", "SSL", false);                                   //Enable SMTP authentication
        $this->mail->Username = System::getSystem("smtp", "username", "root");                     //SMTP username
        $this->mail->Password = System::getSystem("smtp", "password", "root");                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->mail->Port = System::getSystem("smtp", "port", 425);                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    }

    function send($message, $email): bool
    {
        try {
            $this->mail->setFrom($email, System::getSystem("system", "name", "BH验证网"));
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = $message['title'];
            $this->mail->Body = $message['body'];
            $this->mail->send();
            return true;
        } catch (Exception $e) {
        }
        return false;
    }
}
