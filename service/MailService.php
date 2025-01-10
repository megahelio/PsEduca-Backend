<?php

require_once __DIR__ . "/../model/Mail.php";
require_once __DIR__ . "/../core/mail/MailSender.php";

class MailService
{
    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws MailException
     */
    public function sendContactMail(?string $name, ?string $email, ?string $subject, ?string $message): void
    {
        $mail = new Mail($name, $email, $subject, $message);

        Validator::validate($mail, Action::SEND);

        $mailSender = new MailSender();
        $mailSender->send($mail);

    }



}