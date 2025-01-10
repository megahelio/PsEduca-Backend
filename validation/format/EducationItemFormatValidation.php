<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class EducationItemFormatValidation extends BaseFormatValidation {

    public static int $maxFileSize = 10485760; // 10 MB
    private static array $allowedItemTypes = ['MASTER', 'DOCTORADO', 'CURSO'];

    public function __construct() {
        parent::__construct();
    }

    public function validate(Action $action, EducationItem $educationItem): array {
        switch ($action) {

            case Action::GET:
            case Action::DELETE:

                $this->validateId($educationItem->getId());
                break;

            case Action::EDIT:

                $this->validateId($educationItem->getId());

            case Action::ADD:

                $this->validateTitle($educationItem->getTitle());
                $this->validateDescription($educationItem->getDescription(), false, 4, 1000);
                $this->validateLink($educationItem->getReferenceURL(), false);
                $this->validateStartYear($educationItem->getInitYear());
                $this->validateEndYear($educationItem->getEndYear(), $educationItem->getInitYear());
                $this->validateType($educationItem->getType(), EducationItemFormatValidation::$allowedItemTypes);
                $this->validateFile(
                    file: $educationItem->getImage(),
                    fileType: FileType::Image,
                    maxFileSize: EducationItemFormatValidation::$maxFileSize,
                    allowedEmpty: true
                );
                break;

            default:
                break;
        }

        return $this->getErrors();
    }

    // Validar nombre
    private function validateTitle(?string $name): void
    {
        if (strlen($name ?? "") < 4) {
            $this->addError('TITULO_FORMACION_MINIMO_F_KO');
        } elseif (strlen($name) > 254) {
            $this->addError('TITULO_FORMACION_MAXIMO_F_KO');
        } elseif (!preg_match('/^[a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑªº,.\'" ]+$/', $name)) {
            $this->addError('TITULO_FORMACION_CARACTERES_F_KO');
        }
    }

    // Validar anho_inicio
    private function validateStartYear(?string $startYear): void
    {
        if (!is_numeric($startYear?? "")) {
            $this->addError('ANHO_INICIO_INVALIDO_F_KO');
        } elseif (intval($startYear) < 1900) {
            $this->addError('ANHO_INICIO_MINIMO_F_KO');
        } elseif (intval($startYear) > 3000) {
            $this->addError('ANHO_INICIO_MAXIMO_F_KO');
        }
    }

    // Validar anho_fin
    private function validateEndYear(?string $endYear, ?string $initYear): void
    {
        if (!is_null($endYear)){
            if (!is_numeric($endYear)) {
                $this->addError('ANHO_FIN_INVALIDO_F_KO');
            } elseif (intval($endYear) < 1900) {
                $this->addError('ANHO_FIN_MINIMO_F_KO');
            } elseif (intval($endYear) > 3000) {
                $this->addError('ANHO_FIN_MAXIMO_F_KO');
            } elseif (is_numeric($initYear) && intval($endYear) < intval($initYear)) {
                // Comprobar que el año de fin es mayor o igual al año de inicio
                $this->addError('ANHO_FIN_INVALIDO_F_KO');
            }
        }
    }
}
