<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class MemberFormatValidation extends BaseFormatValidation {

    public static int $maxFileSize = 10485760; // 10 MB
    public function __construct() {
        parent::__construct();
    }

    public function validate(Action $action, Member $member): array {
        switch ($action) {

            case Action::GET:
            case Action::DELETE:

                $this->validateId($member->getId());
                break;

            case Action::EDIT:

                $this->validateId($member->getId());

            case Action::ADD:

                $this->validateName($member->getName());
                $this->validateDescription($member->getDescription(), true, 4, 1000);
                $this->validateEmail($member->getEmail(), true);
                $this->validateLink($member->getReferenceURL(), true);
                $this->validateFile(
                    file: $member->getImage(),
                    fileType: FileType::Image,
                    maxFileSize: MemberFormatValidation::$maxFileSize,
                    allowedEmpty: true
                );
                break;

            default:
                break;
        }

        return $this->getErrors();
    }

    // Validar nombre
    private function validateName(?string $name): void
    {
        if (strlen($name ?? "") < 4) {
            $this->addError('NOMBRE_MIEMBRO_MINIMO_F_KO');
        } elseif (strlen($name) > 254) {
            $this->addError('NOMBRE_MIEMBRO_MAXIMO_F_KO');
        } elseif (!preg_match('/^[a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑªº ]+$/', $name)) {
            $this->addError('NOMBRE_MIEMBRO_CARACTERES_F_KO');
        }
    }

}
