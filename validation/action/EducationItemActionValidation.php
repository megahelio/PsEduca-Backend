<?php

class EducationItemActionValidation {

    private array $errors;
    private EducationItemMapper $educationMapper;

    public function __construct() {
        $this->errors = [];
        $this->educationMapper = EducationItemMapper::getInstance();
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }


    /**
     * Valida las reglas específicas según la acción proporcionada.
     *
     * @param Action $action La acción que se va a realizar posteriormente si las comprobaciones son correctas.
     * @param EducationItem $educationItem El objeto de la sección formación.
     * @return array Lista de errores encontrados.
     */
    public function validate(Action $action, EducationItem $educationItem): array {

        $this->errors = match ($action) {

            Action::EDIT, Action::DELETE, Action::GET => $this->validateEducationItemExists($educationItem->getId()),

            default => [],
        };

        return $this->errors;
    }


    /**
     * Comprueba si un item de formación existe por su ID.
     *
     * @param int $educationItemId El ID del item de formación a verificar.
     */
    private function validateEducationItemExists(int $educationItemId): array {
        $errors = [];
        if (!$this->educationMapper->educationItemExists($educationItemId)) {
            $errors[] = 'ITEM_FORMACION_NO_ENCONTRADO_A_KO';
        }
        return $errors;
    }
}
