<?php

namespace app\helpers;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer_bk
{
    public function init()
    {
        // //Create a new PHPMailer instance
        // $mail = new PHPMailer();
        // //Tell PHPMailer to use SMTP
        // $mail->isSMTP();
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // $mail->Host = 'smtp.gmail.com';
        // $mail->Port = 587;
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // $mail->SMTPAuth = true;
        // $mail->Username = 'ranthonyhg@gmail.com';
        // $mail->Password = '47960299G';
        // $mail->setFrom('ranthonyhg@gmail.com', 'User');
        // $mail->addAddress('ranthonyhg@gmail.com', 'Vilma Oblitas');
        // $mail->Subject = 'PHPMailer GMail SMTP test';
        // $mail->msgHTML("Hola a todos!!");
        // $mail->AltBody = 'This is a plain-text message body';

        $this->mail = new PHPMailer(true);
        try {
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->Port       = 587;
            $this->mail->isSMTP();
            $this->mail->SMTPAuth   = true;
            $this->mail->Username = 'vilmaob845@gmail.com';
            $this->mail->Password = 'ob.vilma4252';
            $this->mail->CharSet = PHPMailer::CHARSET_UTF8;
            $this->mail->setFrom('ranthonyhg@gmail.com', 'iWasi');
            $this->mail->addAddress('ranthonyhg@gmail.com', 'Richard');
            $this->mail->isHTML(true);
            $this->mail->Subject = 'informacion';
            $this->mail->Body = '<h1>hola</h1>';
        } catch (Exception $ex) {
            print_r($ex);
            echo $this->mail->ErrorInfo;
        }

        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        if (!$this->mail->send()) {
            echo 'Mailer Error: ' . $this->mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
        
    }
    
}