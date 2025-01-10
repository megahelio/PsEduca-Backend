<?php

class OutreachItemActionValidation {

    private array $errors;
    private OutreachItemMapper $outreachItemMapper;

    public function __construct() {
        $this->errors = [];
        $this->outreachItemMapper = OutreachItemMapper::getInstance();
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }


    /**
     * Valida las reglas específicas según la acción proporcionada.
     *
     * @param Action $action La acción que se va a realizar posteriormente si las comprobaciones son correctas.
     * @param OutreachItem $outreachItem El objeto de la sección divulgación.
     * @return array Lista de errores encontrados.
     */
    public function validate(Action $action, OutreachItem $outreachItem): array {

        $this->errors = match ($action) {

            Action::EDIT, Action::DELETE, Action::GET => $this->validateOutreachItemExists($outreachItem->getId()),

            default => [],
        };

        return $this->errors;
    }


    /**
     * Comprueba si un item de divulgación existe por su ID.
     *
     * @param int $outreachItemId El ID del item de divulgación a verificar.
     */
    private function validateOutreachItemExists(int $outreachItemId): array {
        $errors = [];
        if (!$this->outreachItemMapper->outreachItemExists($outreachItemId)) {
            $errors[] = 'ITEM_DIVULGACION_NO_ENCONTRADO_A_KO';
        }
        return $errors;
    }
}
