<?php

class UserActionValidation {

    private array $errors;
    private UserMapper $userMapper;

    public function __construct() {
        $this->errors = [];
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
     * @param User $user El objeto del usuario.
     * @return array Lista de errores encontrados.
     */
    public function validate(Action $action, User $user): array {

        $this->errors = match ($action) {

            Action::ADD => $this->validateUserNameNotExists($user->getUserName(), null),

            Action::EDIT => array_merge(
                $this->validateUserNameNotExists($user->getUserName(), $user->getId()),
                $this->validateUserExists($user->getId())
            ),

            Action::DELETE, Action::GET => $this->validateUserExists($user->getId()),

            default => [],
        };

        return $this->errors;
    }

    /**
     * Comprueba si un nombre de usuario ya existe.
     *
     * @param string $userName El nombre de usuario a verificar.
     */
    private function validateUserNameNotExists(string $userName, ?string $userId): array {
        $errors = [];

        if ($userId === null) {
            if ($this->userMapper->userNameExists($userName)) {
                $errors[] = 'NOMBRE_USUARIO_YA_EXISTE_A_KO';
            }
        } else {
            if ($this->userMapper->userNameExistsExceptSelf($userName, $userId)) {
                $errors[] = 'NOMBRE_USUARIO_YA_EXISTE_A_KO';
            }
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
}
