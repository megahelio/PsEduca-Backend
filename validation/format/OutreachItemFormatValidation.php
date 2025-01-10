<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class OutreachItemFormatValidation extends BaseFormatValidation {

    public static array $allowedItemTypes = ['PAGINA_INTERNA', 'FICHERO_INTERNO', 'LINK_EXTERNO'];
    public static int $maxImageSize = 10485760; // 10 MiB
    public static int $maxFileSize = 1073741824; // 1GiB

    public function __construct() {
        parent::__construct();
    }

    public function validate(Action $action, OutreachItem $outreachItem): array {
        switch ($action) {

            case Action::GET:
            case Action::DELETE:
                $this->validateId($outreachItem->getId());
                break;

            case Action::EDIT:
                $this->validateId($outreachItem->getId());

            case Action::ADD:
                $this->validateTitle($outreachItem->getTitle());
                $this->validateDescription($outreachItem->getDescription(), true, 4, 1000);
                $this->validateFile(
                    file: $outreachItem->getImage(),
                    fileType: FileType::Image,
                    maxFileSize: OutreachItemFormatValidation::$maxImageSize,
                    allowedEmpty: true
                );
                if ($this->validateType($outreachItem->getType(), OutreachItemFormatValidation::$allowedItemTypes)) {
                    // Validar campos específicos según el tipo de item de divulgación
                    $this->validateOutreachItemLink($outreachItem->getExternalURL(), $outreachItem->getType());
                    $this->validateOutreachItemFile(
                        file: $outreachItem->getFile(),
                        fileType: FileType::All,
                        outreachItemType: $outreachItem->getType(),
                        maxFileSize: OutreachItemFormatValidation::$maxFileSize
                    );
                    $this->validateOutreachItemDetailPage($outreachItem->getPageContent(), $outreachItem->getType());
                }
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

    // Validar página de detalle
    private function validateDetailPage(?string $detailPage, bool $nullable): void {
        if (is_null($detailPage) && !$nullable) {
            $this->addError('PAGINA_DETALLE_MINIMO_F_KO');
        } elseif (!is_null($detailPage)) {
            if (strlen($detailPage) < 4) {
                $this->addError('PAGINA_DETALLE_MINIMO_F_KO');
            } elseif (strlen($detailPage) > 65534) {
                $this->addError('PAGINA_DETALLE_MAXIMO_F_KO');
            }
        }
    }

    private function validateOutreachItemLink(?string $getExternalURL, string $outreachItemType): void
    {
        switch ($outreachItemType) {
            case 'LINK_EXTERNO':
                $this->validateLink($getExternalURL, false);
                break;
            case 'FICHERO_INTERNO':
                if (!is_null($getExternalURL)) {
                    $this->addError('CAMPO_LINK_EXTERNO_INCOMPATIBLE_CON_TIPO_FICHERO_INTERNO_F_KO');
                }
                break;
            case 'PAGINA_INTERNA':
                $this->validateLink($getExternalURL, true);
                break;
        }
    }

    private function validateOutreachItemDetailPage(?string $detailPage, string $outreachItemType): void
    {
        switch ($outreachItemType) {
            case 'LINK_EXTERNO':
                if (!is_null($detailPage)) {
                    $this->addError('CAMPO_PAGINA_DETALLE_INCOMPATIBLE_CON_TIPO_LINK_EXTERNO_F_KO');
                }
                break;
            case 'FICHERO_INTERNO':
                if (!is_null($detailPage)) {
                    $this->addError('CAMPO_PAGINA_DETALLE_INCOMPATIBLE_CON_TIPO_FICHERO_INTERNO_F_KO');
                }
                break;
            case 'PAGINA_INTERNA':
                $this->validateDetailPage($detailPage, false);
                break;
        }
    }

    private function validateOutreachItemFile(?File $file, FileType $fileType, string $outreachItemType, int $maxFileSize): void
    {
        switch ($outreachItemType) {
            case 'FICHERO_INTERNO':
                $this->validateFile(
                    file: $file,
                    fileType: $fileType,
                    maxFileSize: $maxFileSize,
                    allowedEmpty: false
                );
                break;
            case 'LINK_EXTERNO':
                if ($file) {
                    $this->addError('CAMPO_FICHERO_INCOMPATIBLE_CON_TIPO_LINK_EXTERNO_F_KO');
                }
                break;
            case 'PAGINA_INTERNA':
                $this->validateFile(
                    file: $file,
                    fileType: $fileType,
                    maxFileSize: $maxFileSize,
                    allowedEmpty: true
                );
                break;
        }
    }
}
