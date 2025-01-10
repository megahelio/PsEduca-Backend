<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class UserFormatValidation extends BaseFormatValidation {

    public function __construct() {
        parent::__construct();
    }

    public function validate(Action $action, User $user): array {
        $this->resetErrors();

        switch ($action) {

            case Action::GET:
            case Action::DELETE:

                $this->validateId($user->getId());
                break;

            case Action::ADD:

                $this->validateUserName($user->getUserName());
                $this->validateFullName($user->getFullName());
                $this->validatePassword($user->getPassword(), false);
                $this->validateRole($user);

                break;

            case Action::EDIT:

                $this->validateUserName($user->getUserName());
                $this->validateFullName($user->getFullName());
                $this->validatePassword($user->getPassword(), true);
                $this->validateRole($user);
                break;

            case Action::LOGIN:

                $this->validateUserName($user->getUserName());
                $this->validatePassword($user->getPassword(), false);
                break;

            default:
                break;
        }

        return $this->getErrors();
    }

    // Validar nombre_usuario
    private function validateUserName(?string $userName): void
    {
        if (strlen($userName ?? "") < 4) {
            $this->addError('NOMBRE_USUARIO_MINIMO_F_KO');
        } elseif (strlen($userName) > 254) {
            $this->addError('NOMBRE_USUARIO_MAXIMO_F_KO');
        } elseif (!preg_match('/^[a-zA-Z0-9_\-]+$/', $userName)) {
            $this->addError('NOMBRE_USUARIO_CARACTERES_F_KO');
        }
    }

    // Validar nombre_completo
    private function validateFullName(?string $fullName): void
    {
        if (!empty($fullName)) {
            if (strlen($fullName) < 4) {
                $this->addError('NOMBRE_COMPLETO_MINIMO_F_KO');
            } elseif (strlen($fullName) > 254) {
                $this->addError('NOMBRE_COMPLETO_MAXIMO_F_KO');
            } elseif (!preg_match('/^[a-zA-Z0-9_\-áéíóúñÁÉÍÓÚÑªº ]+$/', $fullName)) {
                $this->addError('NOMBRE_COMPLETO_CARACTERES_F_KO');
            }
        }
    }

    // Validar contrasenha
    private function validatePassword(?string $password, bool $allowedEmpty): void {
        if (!$allowedEmpty || !empty($password)) {
            if (strlen($password ?? "") < 4) {
                $this->addError('CONTRASENHA_MINIMO_F_KO');
            } elseif (strlen($password) > 254) {
                $this->addError('CONTRASENHA_MAXIMO_F_KO');
            } elseif (!preg_match('/^[a-zA-Z0-9_\-\$@()\.\+=\/]+$/', $password)) {
                $this->addError('CONTRASENHA_CARACTERES_F_KO');
            }
        }
    }

    // Validar rol
    private function validateRole(User $user): void
    {
        $role = $user->getRole();
        if (!$role instanceof UserRole) {
            if (is_string($role)) {
                $role = UserRole::tryFrom($role);
                if ($role === null) {
                    $this->addError('ROL_F_KO');
                } else {
                    $user->setRole($role);
                }
            } else {
                $this->addError('ROL_F_KO');
            }
        }
    }
}
