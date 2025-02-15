<?php
// file: /core/PDOConnection.php

class PDOConnection {

    private static $db_singleton = null;

    public static function getInstance(): PDO
    {
        if (self::$db_singleton == null) {
            self::$db_singleton = new PDO(
            "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8", // connection string
                DB_USER,
                DB_PASS,
                array( // options
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        }
        return self::$db_singleton;
    }
}
