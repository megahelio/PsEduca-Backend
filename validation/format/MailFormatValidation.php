<?php

class MailFormatValidation
{

    private array $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    // Obtener errores
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validate(Action $action, Mail $mail): array {
        $this->errors = match ($action) {

            Action::SEND => array_merge(
                $this->validateSenderName($mail->getSenderName()),
                $this->validateEmail($mail->getSenderEmail()),
                $this->validateSubject($mail->getSubject()),
                $this->validateMessage($mail->getMessage())
            ),

            default => [],
        };

        return $this->errors;
    }

    // Validar nombre_emisor
    private function validateSenderName(?string $senderName): array
    {
        $errors = [];
        if (strlen($senderName ?? "") < 4) {
            $errors[] = 'NOMBRE_EMISOR_MINIMO_F_KO';
        } elseif (strlen($senderName) > 254) {
            $errors[] = 'NOMBRE_EMISOR_MAXIMO_F_KO';
        } elseif (!preg_match('/^[a-zA-ZñÑ0-9_\-\ áéíóúÁÉÍÓÚªº]+$/', $senderName)) {
            $errors[] = 'NOMBRE_EMISOR_CARACTERES_F_KO';
        }
        return $errors;
    }

    // Validar email_emisor
    private function validateEmail(?string $senderEmail): array
    {
        $errors = [];
        if (!filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'EMAIL_EMISOR_INVALIDO_F_KO';
        }
        return $errors;
    }

    // Validar asunto
    private function validateSubject(?string $subject): array
    {
        $errors = [];
        if (strlen($subject ?? "") < 4) {
            $errors[] = 'ASUNTO_MINIMO_F_KO';
        } elseif (strlen($subject) > 50) {
            $errors[] = 'ASUNTO_MAXIMO_F_KO';
        } elseif (!preg_match('/^[a-zA-ZñÑ0-9_\-\ áéíóúÁÉÍÓÚ\.\¿\?\',]+$/', $subject)) {
            $errors[] = 'ASUNTO_CARACTERES_F_KO';
        }
        return $errors;
    }

    // Validar mensaje
    private function validateMessage(?string $message): array
    {
        $errors = [];
        if (strlen($message ?? "") < 10) {
            $errors[] = 'MENSAJE_MINIMO_F_KO';
        } elseif (strlen($message) > 5000) {
            $errors[] = 'MENSAJE_MAXIMO_F_KO';
        } elseif (!preg_match('/^[\s\S]*$/', $message)) { // Permitir cualquier carácter imprimible
            $errors[] = 'MENSAJE_CARACTERES_F_KO';
        }
        return $errors;
    }
}