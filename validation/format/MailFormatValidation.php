<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class MailFormatValidation extends BaseFormatValidation
{

    public function __construct()
    {
        parent::__construct();
    }

    public function validate(Action $action, Mail $mail): array {

        switch ($action) {
            case Action::SEND:

                $this->validateSenderName($mail->getSenderName());
                $this->validateEmail($mail->getSenderEmail(), false);
                $this->validateSubject($mail->getSubject());
                $this->validateMessage($mail->getMessage());
                break;

            default:
                break;
        }

        return $this->getErrors();
    }

    // Validar nombre_emisor
    private function validateSenderName(?string $senderName): void
    {
        if (strlen($senderName ?? "") < 4) {
            $this->addError('NOMBRE_EMISOR_MINIMO_F_KO');
        } elseif (strlen($senderName) > 254) {
            $this->addError('NOMBRE_EMISOR_MAXIMO_F_KO');
        } elseif (!preg_match('/^[a-zA-ZñÑ0-9_\-\ áéíóúÁÉÍÓÚªº]+$/', $senderName)) {
            $this->addError('NOMBRE_EMISOR_CARACTERES_F_KO');
        }
    }

    // Validar asunto
    private function validateSubject(?string $subject): void
    {
        if (strlen($subject ?? "") < 4) {
            $this->addError('ASUNTO_MINIMO_F_KO');
        } elseif (strlen($subject) > 50) {
            $this->addError('ASUNTO_MAXIMO_F_KO');
        } elseif (!preg_match('/^[a-zA-ZñÑ0-9_\-\ áéíóúÁÉÍÓÚ\.\¿\?\',]+$/', $subject)) {
            $this->addError('ASUNTO_CARACTERES_F_KO');
        }
    }

    // Validar mensaje
    private function validateMessage(?string $message): void
    {
        if (strlen($message ?? "") < 10) {
            $this->addError('MENSAJE_MINIMO_F_KO');
        } elseif (strlen($message) > 5000) {
            $this->addError('MENSAJE_MAXIMO_F_KO');
        } elseif (!preg_match('/^[\s\S]*$/', $message)) { // Permitir cualquier carácter imprimible
            $this->addError('MENSAJE_CARACTERES_F_KO');
        }
    }
}