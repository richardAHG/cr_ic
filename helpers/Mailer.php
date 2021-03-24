<?php

namespace app\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public $path = "@app/mails";
    private $mail;

    private function init()
    {
        $this->mail = new PHPMailer(true);
        try {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->Port       = 587;
            $this->mail->isSMTP();
            $this->mail->SMTPAuth   = true;
            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $this->mail->Username = 'ranthonyhg@gmail.com';
            $this->mail->Password = '47960299G';
            $this->mail->CharSet = PHPMailer::CHARSET_UTF8;
            $this->mail->setFrom('richard@cuborojo.com', 'CREDICORP');
        } catch (Exception $ex) {
            echo $this->mail->ErrorInfo;
        }
    }

    /**
     * Envía correo electrónico
     *
     * @param [string|array] $address
     * @param [string] $subject
     * @param [string|html] $body
     * @param [string|array] $attachment
     * @param [string|array] $cc
     * @param [string|array] $bcc
     * @return bool
     */
    public function send($address, $subject, $body, $attachment = null, $cc = null, $bcc = null)
    {
        try {
            // $mail->Timeout=200;
            // $mail->ClearAddresses();
            // $mail->AddAddress ($adress);
            // $mail->Subject = $subject;
            // $mail->Body = utf8_decode($body);
            // $mail->AltBody = $body;
            // $mail->Send();

            $this->init();
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            #destinatarios
            if (is_array($address)) {
                foreach ($address as $destinatario) {
                    $this->mail->addAddress($destinatario, "");
                }
            } else {
                $this->mail->addAddress($address, "");
            }

            #correos en copia
            if (is_array($cc) && $cc !== null) {
                foreach ($cc as $destinatario) {
                    $this->mail->addCC($destinatario);
                }
            } elseif ($cc !== null) {
                $this->mail->addCC($cc);
            }

            #correos en copia oculta
            if (is_array($bcc) && $bcc !== null) {
                foreach ($bcc as $destinatario) {
                    $this->mail->addCC($destinatario);
                }
            } elseif ($bcc !== null) {
                $this->mail->addCC($bcc, "");
            }

            #adjunta archivo(s)
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    $this->mail->AddAttachment($file);
                }
            } elseif ($attachment !== null) {
                $this->mail->AddAttachment($attachment);
            }

            // if (!$this->mail->send()) {
            //     $message = "Error: {$this->mail->ErrorInfo}";
            // } else {
            //     $message = "Mensaje Enviado!";
            // }

           
            
            $result = $this->mail->send();
        } catch (Exception $ex) {
            // print_r($ex);
            throw "Error {$this->mail->ErrorInfo}";
        }
       
        $this->mail->ClearAddresses();

        return $result;
    }
}
