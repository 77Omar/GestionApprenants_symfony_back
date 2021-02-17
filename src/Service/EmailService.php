<?php
 namespace App\Service;


   class EmailService {
       public function sendEmail($name, \Swift_Mailer $mailer)
       {
           $message = (new \Swift_Message('Hello Email'))
               ->setFrom('fayeomzolive@gmail.com')
               ->setTo($name)
               ->setBody('Félicitations!!! vous avez été sélectionné(e) suite à votre test dentré à la Sonatel Academy!')
           ;

           $mailer->send($message);

           // ...
       }
   }
