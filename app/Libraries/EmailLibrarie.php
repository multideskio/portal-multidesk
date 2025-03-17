<?php

namespace App\Libraries;

class EmailLibrarie
{
   protected $email;
   public function __construct()
   {
      $this->email = service('email');
   }
   public function configEmail(): void
   {
      $config['protocol'] = 'smtp';
      $config['SMTPHost'] = env('SMTP_HOST');
      $config['SMTPUser'] = env('SMTP_USER');
      $config['SMTPPass'] = env('SMTP_PASS');
      $config['SMTPPort'] = (int)env('SMTP_PORT');
      $config['SMTPCrypto'] = env('SMTP_CRYPTO');
      $config['mailType'] = 'html';
      $this->email->initialize($config);
   }

   public function sendEmail($to, $subject, $message): void
   {
      $this->configEmail();
      $this->email->setFrom(env('SENDER_EMAIL'), 'Portal Multidesk');
      $this->email->setTo($to);
      $this->email->setSubject($subject);
      $this->email->setMessage($message);
      $this->email->send();
   }
}