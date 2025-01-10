<?php

enum FileType: string
{
    case Image = "IMAGEN";
    case Document = "DOCUMENTO";
    case All = "FICHERO";
}

class BaseFormatValidation
{

    private array $errors;
    private static array $allowedImageMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/svg+xml',
        'image/webp',
        'image/avif',
        'image/x-icon'
    ];
    private static array $allowedDocumentMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/zip',
        'application/x-rar-compressed',
        'application/x-7z-compressed',
        'application/x-tar',
        'application/x-gzip',
        'application/x-bzip2',
        'application/x-xz',
        'application/x-rar'
    ];
//    private static array $allowedGenericMimeTypes = [
//        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/avif', 'image/x-icon',
//        'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
//        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//        'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
//        'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed', 'application/x-tar',
//        'application/x-gzip', 'application/x-bzip2', 'application/x-xz', 'application/x-rar',
//
//        'application/json', 'application/xml',
//        'text/plain', 'text/html', 'text/css', 'text/javascript', 'text/csv', 'text/calendar', 'text/markdown',
//        'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/midi', 'audio/aac', 'audio/x-flac', 'audio/x-wav', 'audio/x-midi',
//        'video/mp4', 'video/x-msvideo', 'video/x-matroska', 'video/quicktime', 'video/webp', 'video/ogg',
//    ];

    public function __construct() {
        $this->errors = [];
    }

    public function resetErrors(): void {
        $this->errors = [];
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }

    // Añadir error a los existentes
    public function addError(string $error): void
    {
        $this->errors[] =  $error;
    }

    // Validar id
    protected function validateId(?string $id): void
    {
        if (strlen($id ?? "") < 1) {
            $this->addError('ID_MINIMO_F_KO');
        } elseif (!is_numeric($id)) {
            $this->addError('ID_INVALIDO_F_KO');
        } elseif (intval($id) < 1) {
            $this->addError('ID_INVALIDO_F_KO');
        }
    }

    // Validar email_emisor
    protected function validateEmail(?string $email, bool $allowedEmpty): void
    {
        if (!$allowedEmpty || !empty($email)){
//            if (strlen($email) < 4) {
//                $this->addError('EMAIL_MINIMO_F_KO');
//            } elseif (strlen($email) > 254) {
//                $this->addError('EMAIL_MAXIMO_F_KO');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                $this->addError('EMAIL_EMISOR_INVALIDO_F_KO');
                $this->addError('EMAIL_INVALIDO_F_KO'); // todo: provisionalmente se usa el nuevo error. Pendiente de revisar lo que quiere Jose
            }
        }
    }

    // Validar fichero
    protected function validateFile(?File $file, FileType $fileType, int $maxFileSize, bool $allowedEmpty): void
    {
        if ($file) {
            if ($file->getFileSize() > $maxFileSize) {
                $this->addError($fileType->value.'_TAMAÑO_F_KO');
            }

            $allowedMimeTypes = match ($fileType) {
                FileType::Image => self::$allowedImageMimeTypes,
                FileType::Document => self::$allowedDocumentMimeTypes,
                FileType::All => true,
            };

            if (!$allowedMimeTypes && !in_array($file->getMimeType(), $allowedMimeTypes)) {
                $this->addError($fileType->value.'_FORMATO_F_KO');
            }
        } elseif (!$allowedEmpty) {
            $this->addError($fileType->value.'_OBLIGATORIO_F_KO');
        }
    }

    // Validar link de recurso (URL)
    protected function validateLink(?string $link, bool $allowedEmpty): void
    {
        if (!$allowedEmpty || !empty($link)) {
            if (strlen($link ?? "") < 4) {
                $this->addError('LINK_MINIMO_F_KO');
            } elseif (strlen($link) > 254) {
                $this->addError('LINK_MAXIMO_F_KO');
            } elseif (!filter_var($link, FILTER_VALIDATE_URL)) {
                $this->addError('LINK_INVALIDO_F_KO');
            }
        }
    }

    // Validar descripción
    protected function validateDescription(?string $description, bool $allowedEmpty, int $minCharacters, int $maxCharacters): void
    {
        if (!$allowedEmpty || !empty($description)) {
            if (strlen($description ?? "") < $minCharacters) {
                $this->addError('DESCRIPCION_MINIMO_F_KO');
            } elseif (strlen($description) > $maxCharacters) {
                $this->addError('DESCRIPCION_MAXIMO_F_KO');
            } elseif (!preg_match('/^[^\a]*$/', $description)) {
                $this->addError('DESCRIPCION_CARACTERES_F_KO');
            }
        }
    }

    // Validar tipo
    protected function validateType(?string $type, array $validTypes): bool {
        if (!in_array($type, $validTypes, true)) {
            $this->addError('TIPO_F_KO');
            return false;
        }
        return true;
    }

}