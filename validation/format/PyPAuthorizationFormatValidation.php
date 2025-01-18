<?php

require_once __DIR__ . "/BaseFormatValidation.php";

class PyPAuthorizationFormatValidation extends BaseFormatValidation {

    public function __construct() {
        parent::__construct();
    }

    public function validate(Action $action, PyPAuthorization $pypItem): array {
        switch ($action) {


            case Action::DELETE:
            case Action::ADD:
                $this->validateId($pypItem->getIdPyPItem(), 'PYP_ITEM_');
                $this->validateId($pypItem->getIdUser(), 'USUARIO_');
                break;

            default:
                break;
        }

        return $this->getErrors();
    }
}
