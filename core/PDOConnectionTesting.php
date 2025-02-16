<?php
// file: /core/PDOConnectionTesting.php

class PDOConnectionTesting {

    private static $db_singleton = null;

    public static function getInstance(): PDO
    {
        if (self::$db_singleton == null) {
            self::$db_singleton = new PDO(
            "mysql:host=".DB_TEST_HOST.";port=".DB_TEST_PORT.";dbname=".DB_TEST_NAME.";charset=utf8", // connection string
                DB_TEST_USER,
                DB_TEST_PASS,
                array( // options
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        }
        return self::$db_singleton;
    }
}
