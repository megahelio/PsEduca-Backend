<?php
class UserFormatValidation {

    private array $errors;

    public function __construct() {
        $this->errors = [];
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }

    public function validate(Action $action, User $user): array {
        $this->errors = match ($action) {

            Action::GET, Action::DELETE => $this->validateId($user->getId()),

            Action::ADD, Action::EDIT => array_merge(
                $this->validateUserName($user->getUserName()),
                $this->validateFullName($user->getFullName()),
                $this->validatePassword($user->getPassword()),
                $this->validateRole($user)
            ),

            Action::LOGIN => array_merge(
                $this->validateUserName($user->getUserName()),
                $this->validatePassword($user->getPassword())
            ),

            default => [],
        };

        return $this->errors;
    }

    // Validar nombre_usuario
    private function validateUserName(?string $userName): array {
        $errors = [];
        if (strlen($userName ?? "") < 4) {
            $errors[] = 'NOMBRE_USUARIO_MINIMO_F_KO';
        } elseif (strlen($userName) > 254) {
            $errors[] = 'NOMBRE_USUARIO_MAXIMO_F_KO';
        } elseif (!preg_match('/^[a-zA-Z0-9_\-]+$/', $userName)) {
            $errors[] = 'NOMBRE_USUARIO_CARACTERES_F_KO';
        }
        return $errors;
    }

    // Validar nombre_completo
    private function validateFullName(?string $fullName): array {
        $errors = [];
        if (!empty($fullName)) {
            if (strlen($fullName) > 254) {
                $errors[] = 'NOMBRE_COMPLETO_MAXIMO_F_KO';
            } elseif (!preg_match('/^[a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑªº ]+$/', $fullName)) {
                $errors[] = 'NOMBRE_COMPLETO_CARACTERES_F_KO';
            }
        }
        return $errors;
    }

    // Validar contrasenha
    private function validatePassword(?string $password): array {
        $errors = [];
        if (strlen($password ?? "") < 4) {
            $errors[] = 'CONTRASENHA_MINIMO_F_KO';
        } elseif (strlen($password) > 254) {
            $errors[] = 'CONTRASENHA_MAXIMO_F_KO';
        } elseif (!preg_match('/^[a-zA-Z0-9_\-\$@()\.\+=\/]+$/', $password)) {
            $errors[] = 'CONTRASENHA_CARACTERES_F_KO';
        }
        return $errors;
    }

    // Validar rol
    private function validateRole(User $user): array {
        $errors = [];

        $role = $user->getRole();
        if (!$role instanceof UserRole) {
            if (is_string($role)) {
                $role = UserRole::tryFrom($role);
                if ($role === null) {
                    $errors[] = 'ROL_F_KO';
                } else {
                    $user->setRole($role);
                }
            } else {
                $errors[] = 'ROL_F_KO';
            }
        }

        return $errors;
    }

    private function validateId(?string $id): array {
        $errors = [];
        if (strlen($id ?? "") < 1) {
            $errors[] = 'ID_MINIMO_F_KO';
        } elseif (!is_numeric($id)) {
            $errors[] = 'ID_INVALIDO_F_KO';
        } elseif (intval($id) < 1) {
            $errors[] = 'ID_INVALIDO_F_KO';
        }
        return $errors;
    }
}
