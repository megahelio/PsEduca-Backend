<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class CatalogueItemFormatValidation extends BaseFormatValidation {

    public static array $allowedImageMimeTypes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/avif', 'image/x-icon'
    ];
    public static int $maxImageSize = 10485760; // 10 MiB
    public static int $maxFileSize = 1073741824; // 1 GiB

    public function __construct() {
        parent::__construct();
    }

    public function validate(Action $action, CatalogueItem $catalogueItem): array {

        switch ($action) {
            case Action::GET:
            case Action::DELETE:
                $this->validateId($catalogueItem->getId());
                break;

            case Action::EDIT:
                $this->validateId($catalogueItem->getId());

                foreach ($catalogueItem->getNewFiles() as $file) {
                    $this->validateFile($file, FileType::All, CatalogueItemFormatValidation::$maxFileSize, false);
                }

                foreach ($catalogueItem->getNewLinks() as $link) {
                    $this->validateLink($link->getURL(), false);
                }

                foreach ($catalogueItem->getFilesToDelete() as $file) {
                    $this->validateId($file->getId());
                }

                foreach ($catalogueItem->getLinksToDelete() as $link) {
                    $this->validateId($link->getId());
                }

            case Action::ADD:
                $this->validateString('ACRONIMO', $catalogueItem->getAcronym(), 4, 254, '/^[a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑªº,.\'" ]+$/', false);
                $this->validateString('NOMBRE', $catalogueItem->getName(), 4, 1000, '/^[^\a]*$/', false);
                $this->validateNumber('EDAD_MES_MIN', $catalogueItem->getMonthMinAge(), 0, 12, true);
                $this->validateNumber('EDAD_AÑO_MIN', $catalogueItem->getYearMinAge(), 0, 150, true);
                $this->validateNumber('EDAD_MES_MAX', $catalogueItem->getMonthMaxAge(), 0, 12, true);
                $this->validateNumber('EDAD_AÑO_MAX', $catalogueItem->getYearMaxAge(), 0, 150, true);
                $this->validateString('AUTORES', $catalogueItem->getAuthors(), 4, 1000, '/^[^\a]*$/', false);
                $this->validateNumber('TIEMPO', $catalogueItem->getLength(), 0, 9999);
                $this->validateString('DESCRIPCION', $catalogueItem->getDescription(), 4, 65535, '/^[^\a]*$/', false);
                $this->validateString('OBSERVACIONES', $catalogueItem->getNote(), 4, 65535, '/^[^\a]*$/', true);
                $this->validateFile($catalogueItem->getImage(), FileType::Image, CatalogueItemFormatValidation::$maxImageSize, true);
                $this->validateFilterValues('AREA', $catalogueItem->getAreas(), 4, 254, false);
                $this->validateFilterValues('ETIQUETA', $catalogueItem->getTags(), 4, 254, true);
                $this->validateFilterValues('TIPO_RECURSO', $catalogueItem->getResourceTypes(), 4, 254, false);
                $this->validateFilterValues('FORMATO', $catalogueItem->getFormats(), 4, 254, false);
                $this->validateFilterValues('APLICACION', $catalogueItem->getApplicationModes(), 4, 254, false);
                break;

            default:
                break;
        }

        return $this->getErrors();
    }

    private function validateFilterValues(string $field, ?array $values, int $minLength, int $maxLength, bool $allowNull): void {
        if ($allowNull && is_null($values)) {
            return;
        }

        foreach ($values as $value) {
            $this->validateString($field, $value, $minLength, $maxLength, '/^[a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑªº,.\'" ]+$/', false);
        }
    }

    private function validateString(string $field, ?string $value, int $minLength, int $maxLength, string $pattern,
                                    bool $allowNull): void {
        if ($allowNull && is_null($value)) {
            return;
        }

        if (!$allowNull && (is_null($value) || strlen($value) < $minLength)) {
            $this->addError(strtoupper($field) . '_MINIMO_F_KO');
        } elseif (strlen($value) > $maxLength) {
            $this->addError(strtoupper($field) . '_MAXIMO_F_KO');
        } elseif (!preg_match($pattern, $value)) {
            $this->addError(strtoupper($field) . '_INVALIDO_F_KO');
        }
    }

    private function validateNumber(string $field, ?string $value, int $min, int $max, bool $allowNull = false): void {
        if ($allowNull && is_null($value)) {
            return;
        }

        if (!is_numeric($value)) {
            $this->addError(strtoupper($field) . '_CARACTERES_F_KO');
        } elseif ($value < $min) {
            $this->addError(strtoupper($field) . '_MINIMO_F_KO');
        } elseif ($value > $max) {
            $this->addError(strtoupper($field) . '_MAXIMO_F_KO');
        }
    }
}
