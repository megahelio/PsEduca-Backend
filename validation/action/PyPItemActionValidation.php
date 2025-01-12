<?php

class PyPItemActionValidation {

    private array $errors;
    private PyPItemMapper $pypItemMapper;

    public function __construct() {
        $this->errors = [];
        $this->pypItemMapper = PyPItemMapper::getInstance();
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }


    /**
     * Valida las reglas específicas según la acción proporcionada.
     *
     * @param Action $action La acción que se va a realizar posteriormente si las comprobaciones son correctas.
     * @param PyPItem $pypItem El objeto de la sección pruebas y programas.
     * @return array Lista de errores encontrados.
     */
    public function validate(Action $action, PyPItem $pypItem): array {

        $this->errors = match ($action) {

            Action::EDIT, Action::DELETE, Action::GET => $this->validatePyPItemExists($pypItem->getId()),

            default => [],
        };

        return $this->errors;
    }


    /**
     * Comprueba si un item de pruebas y programas existe por su ID.
     *
     * @param int $pypItemId El ID del item de pruebas y programas a verificar.
     */
    private function validatePyPItemExists(int $pypItemId): array {
        $errors = [];
        if (!$this->pypItemMapper->pypItemExists($pypItemId)) {
            $errors[] = 'ITEM_PYP_NO_ENCONTRADO_A_KO';
        }
        return $errors;
    }

    // Todo: Pendiente de implementar el apartado de permisos de PyP
    private function validateHasPermission(int $pypItemId, int $userId): array {
        $errors = [];
        if (!$this->pypItemMapper->hasPermission($pypItemId, $userId)) {
//            $errors[] = 'FORBIDDEN_ACCESS_KO';
            BaseController::generateHttpResponse(403, array(ResponseCodes::FORBIDDEN_ACCESS_KO));
        }
        return $errors;
    }
}
