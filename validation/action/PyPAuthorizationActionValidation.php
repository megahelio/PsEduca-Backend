<?php

class PyPAuthorizationActionValidation {

    private array $errors;
    private PyPAuthorizationMapper $pypAuthorizationMapper;
    private PyPItemMapper $pypItemMapper;
    private UserMapper $userMapper;

    public function __construct() {
        $this->errors = [];
        $this->pypAuthorizationMapper = PyPAuthorizationMapper::getInstance();
        $this->pypItemMapper = PyPItemMapper::getInstance();
        $this->userMapper = UserMapper::getInstance();
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }


    /**
     * Valida las reglas específicas según la acción proporcionada.
     *
     * @param Action $action La acción que se va a realizar posteriormente si las comprobaciones son correctas.
     * @param PyPAuthorization $pypAuthorization El objeto de la sección pruebas y programas.
     * @return array Lista de errores encontrados.
     */
    public function validate(Action $action, PyPAuthorization $pypAuthorization): array {

        $this->errors = match ($action) {

            Action::DELETE => $this->validatePyPAuthorizationExists($pypAuthorization->getIdPyPItem(), $pypAuthorization->getIdUser()),

            Action::ADD => array_merge(
                $this->validatePyPAuthorizationNotExists($pypAuthorization->getIdPyPItem(), $pypAuthorization->getIdUser()),
                $this->validateUserExists($pypAuthorization->getIdUser()),
                $this->validatePyPItemExists($pypAuthorization->getIdPyPItem())
            ),

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

    /**
     * Comprueba si un usuario existe por su ID.
     *
     * @param int $userId El ID del usuario a verificar.
     */
    private function validateUserExists(int $userId): array {
        $errors = [];
        if (!$this->userMapper->userExists($userId)) {
            $errors[] = 'USUARIO_NO_ENCONTRADO_A_KO';
        }
        return $errors;
    }

    private function validatePyPAuthorizationExists(string $pypItemId, string $userId): array
    {
        $errors = [];
        if (!$this->pypAuthorizationMapper->pypAuthorizationExists($pypItemId, $userId)) {
            $errors[] = 'AUTORIZACION_PYP_NO_ENCONTRADA_A_KO';
        }
        return $errors;
    }

    private function validatePyPAuthorizationNotExists(string $pypItemId, string $userId): array
    {
        $errors = [];
        if ($this->pypAuthorizationMapper->pypAuthorizationExists($pypItemId, $userId)) {
            $errors[] = 'AUTORIZACION_PYP_YA_EXISTE_A_KO';
        }
        return $errors;
    }
}
