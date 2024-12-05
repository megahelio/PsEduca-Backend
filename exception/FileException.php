<?php

class FileException extends Exception
{
    public function __construct($msg = "Something went wrong with the file or the file system.")
    {
        parent::__construct($msg);
    }

}