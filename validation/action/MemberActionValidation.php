<?php

class MemberActionValidation {

    private array $errors;
    private MemberMapper $memberMapper;

    public function __construct() {
        $this->errors = [];
        $this->memberMapper = MemberMapper::getInstance();
    }

    // Obtener errores
    public function getErrors(): array {
        return $this->errors;
    }


    /**
     * Valida las reglas específicas según la acción proporcionada.
     *
     * @param Action $action La acción que se va a realizar posteriormente si las comprobaciones son correctas.
     * @param Member $member El objeto del usuario.
     * @return array Lista de errores encontrados.
     */
    public function validate(Action $action, Member $member): array {

        $this->errors = match ($action) {

            Action::EDIT, Action::DELETE, Action::GET => $this->validateMemberExists($member->getId()),

            default => [],
        };

        return $this->errors;
    }


    /**
     * Comprueba si un usuario existe por su ID.
     *
     * @param int $memberId El ID del usuario a verificar.
     */
    private function validateMemberExists(int $memberId): array {
        $errors = [];
        if (!$this->memberMapper->memberExists($memberId)) {
            $errors[] = 'USUARIO_NO_ENCONTRADO_A_KO';
        }
        return $errors;
    }
}
