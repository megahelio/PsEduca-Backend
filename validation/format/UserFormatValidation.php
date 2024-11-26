<?php
class UserFormatValidation {

    private $errors = [];

    // Validar el usuario
    public function validate(User $user): array {
        $this->errors = array_merge(
            $this->validateUserName($user->getUserName()),
            $this->validateFullName($user->getFullName()),
            $this->validatePassword($user->getPassword()),
            $this->validateRole($user->getRole()->name)
        );
        return $this->errors;
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }

    // Validar nombre_usuario
    private function validateUserName(string $userName): array {
        $errors = [];
        if (empty($userName)) {
            $errors[] = 'NOMBRE_USUARIO_VACIO_F_KO';
        } elseif (strlen($userName) > 254) {
            $errors[] = 'NOMBRE_USUARIO_MAXIMO_F_KO';
        } elseif (!preg_match('/^[a-zA-Z0-9_\-áéíóúÁÉÍÓÚ]+$/', $userName)) {
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
            } elseif (!preg_match('/^[a-zA-Z0-9_\-áéíóúÁÉÍÓÚªº ]+$/', $fullName)) {
                $errors[] = 'NOMBRE_COMPLETO_CARACTERES_F_KO';
            }
        }
        return $errors;
    }

    // Validar contrasenha
    private function validatePassword(string $password): array {
        $errors = [];
        if (strlen($password) < 4) {
            $errors[] = 'CONTRASENHA_MINIMO_F_KO';
        } elseif (strlen($password) > 254) {
            $errors[] = 'CONTRASENHA_MAXIMO_F_KO';
        } elseif (!preg_match('/^[a-zA-Z0-9_\-@$/\\]+$/', $password)) {
            $errors[] = 'CONTRASENHA_CARACTERES_F_KO';
        }
        return $errors;
    }

    // Validar rol
    private function validateRole(string $rol): array {
        $errors = [];
        $validRoles = ['ADMIN_GLOBAL', 'GESTOR_CATALOGO', 'USUARIO_PYP'];
        if (!in_array($rol, $validRoles)) {
            $errors[] = 'ROL_F_KO';
        }
        return $errors;
    }
}
