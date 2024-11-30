<?php

class MailException extends Exception
{
    public function __construct($msg="Something went wrong with the mail") {
        parent::__construct($msg);
    }
}
