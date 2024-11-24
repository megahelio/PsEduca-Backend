<?php

namespace exception;

use Exception;

class NotFoundException extends Exception {

    public function __construct($msg=NULL){
        parent::__construct($msg);
    }
}