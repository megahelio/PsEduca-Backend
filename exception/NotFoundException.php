<?php

namespace exception;

use Exception;

class NotFoundException extends Exception {

    public function __construct($msg="Something was not found") {
        parent::__construct($msg);
    }
}