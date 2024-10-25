<?php

require_once (__DIR__ . '/../mapper/UserMapper.php');

class AuthService
{
    private UserMapper $userMapper;

    private static $jwt_key = 'jwt_key';

    public function __construct ()
    {
        $this->userMapper = new UserMapper();
    }

    public function login($user_name, $user_password) {
        // Comprueba si el usuario existe

        try {
            // Verifica si la contraseña es correcta
            if ($this->userMapper->isValidUser($user_name, $user_password)) {

                $payload = [
                    'iss' => 'http://localhost',
                    'aud' => $user_name,
                    'iat' => time(),
                    'exp' => time() + (86400 * 30) // Los tokens expiran en 1 día
                ];

                $jwt_token = JWT::encode($payload, AuthService::$jwt_key, 'HS256');

                // Devuelve una respuesta HTTP 200
                return array(
                    "user_name" => $user_name,
                    "jwt_token" => $jwt_token
                );

            } else {
                // Si la contraseña es incorrecta, devuelve una respuesta HTTP 401
                parent::error401("Contraseña incorrecta o usuario no existente.");
            }
        } catch (Exception $e) {
            // Si la base de datos falla o hay un error imprevisto
            parent::error500();
        }
    }
}