<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class PyPItemFormatValidation extends BaseFormatValidation {

    public static array $allowedItemTypes = ['PAGINA_INTERNA', 'FICHERO_INTERNO', 'LINK_EXTERNO'];
    public static int $maxImageSize = 10485760; // 10 MiB
    public static int $maxFileSize = 1073741824; // 1GiB

    public function __construct() {
        parent::__construct();
    }

    public function validate(Action $action, PyPItem $pypItem): array {
        switch ($action) {

            case Action::GET:
            case Action::DELETE:
                $this->validateId($pypItem->getId());
                break;

            case Action::EDIT:
                $this->validateId($pypItem->getId());

            case Action::ADD:
                $this->validateTitle($pypItem->getTitle());
                $this->validateDescription($pypItem->getDescription(), true, 4, 1000);
                $this->validateLink($pypItem->getExternalURL(), true, 1024);
                $this->validateFile(
                    file: $pypItem->getImage(),
                    fileType: FileType::Image,
                    maxFileSize: PyPItemFormatValidation::$maxImageSize,
                    allowedEmpty: true
                );
                break;

            default:
                break;
        }

        return $this->getErrors();
    }

    // Validar título
    private function validateTitle(?string $title): void {
        if (strlen($title ?? "") < 4) {
            $this->addError('TITULO_MINIMO_F_KO');
        } elseif (strlen($title) > 254) {
            $this->addError('TITULO_MAXIMO_F_KO');
        } elseif (!preg_match('/^[a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑªº,.\'" ]+$/', $title)) {
            $this->addError('TITULO_CARACTERES_F_KO');
        }
    }
}
