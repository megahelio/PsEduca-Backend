<?php
//file: core/ValidationException.php

namespace exception;
use Exception;

/**
 * Class ValidationException
 *
 * A simple Exception including an array of errors
 * useful for form validation.
 * The errors array contains validation errors, normally
 * indexed by form named parameters.
 */
class ValidationException extends Exception
{

    /**
     * Array of errors
     */
    private array $errors;

    public function __construct(array $errors, $msg = "Validation errors")
    {
        parent::__construct($msg);
        $this->errors = $errors;
    }

    /**
     * Gets the validation errors
     *
     * @return array The validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
