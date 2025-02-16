<?php
// file: /core/PDOConnection.php

class PDOConnection
{

    private static ?PDO $db_singleton_prod = null;
    private static ?PDO $db_singleton_test = null;
    public static bool $test = false;

    public static function getInstance(): PDO
    {
        if (self::$test) {
            if (self::$db_singleton_test == null) {
                error_log ("Using test database");
                self::$db_singleton_test = new PDO(
                    "mysql:host=" . DB_TEST_HOST . ";port=" . DB_TEST_PORT . ";dbname=" . DB_TEST_NAME . ";charset=utf8", // connection string
                    DB_TEST_USER,
                    DB_TEST_PASS,
                    array( // options
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    )
                );
            }
            return self::$db_singleton_test;
        } else {
            if (self::$db_singleton_prod == null) {
                error_log("Using prod database");
                self::$db_singleton_prod = new PDO(
                    "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8", // connection string
                    DB_USER,
                    DB_PASS,
                    array( // options
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    )
                );
            }
            return self::$db_singleton_prod;
        }
    }


    public static function setTest(bool $test): void
    {
        error_log("Setting test mode to " . ($test ? "true" : "false"));
        self::$test = $test;
    }
}
