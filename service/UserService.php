<?php

use exception\NotFoundException;
use Firebase\JWT\JWT;

require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../mapper/UserMapper.php";
require_once __DIR__ . "/../core/Validator.php";
require_once __DIR__ . "/../exception/NotFoundException.php";

/**
 * Class UserService
 *
 * Contains operations for adding, deleting, and getting user information.
 */
class UserService {

    private UserMapper $userMapper;
    private Validator $validator;

    public function __construct() {
        $this->userMapper = new UserMapper();
        $this->validator = new Validator();
    }

    /**
     * @throws NotFoundException
     */
    public function login($userName, $userPassword): array
    {
        // Verifica si la contraseña es correcta
        $user = $this->userMapper->getUserInfoFromUserName($userName);
        if ($user != null) {

            if (!password_verify($userPassword, $user->getPassword())) {
                // No damos detalles sobre si el usuario o la contraseña son incorrectos, se trata del mismo modo.
                throw new NotFoundException();
            }

            $payload = [
                'iss' => SERVER_URL,
                'sub' => $user->getId(),
                'iat' => time(),
                'exp' => time() + (86400 * 30), // Los tokens expiran en 1 día
                'role' => $user->getRole()->name,
            ];

            $jwt_token = JWT::encode($payload, JWT_KEY, 'HS256');

            // Devuelve una respuesta HTTP 200
            return array(
                "user" => $user,
                "jwtToken" => $jwt_token,
                "tokenExpirationDate" => time() + (86400 * 30)
            );

        } else {
            // No damos detalles sobre si el usuario o la contraseña son incorrectos, se trata del mismo modo.
            throw new NotFoundException();
        }
    }

    /**
     * @throws NotFoundException
     */
    public function get($userId) : User
    {
        $user = $this->userMapper->getUserInfo($userId);
        if ($user != null) {
            return $user;
        } else {
            throw new NotFoundException();
        }
    }

    /**
     * @throws ValidationException
     */
    public function add($username, $fullName, $passwd, $role): void
    {

        $user = new User(null, $username, password_hash($passwd, PASSWORD_BCRYPT), $role, $fullName);

//        $format_errors = $this->validator->validate($user);

//        if (sizeof($format_errors) > 0) {
//            throw new ValidationException($format_errors);
//        }

        $this->userMapper->save($user);
    }

    public function delete($userName): void
    {
        $this->userMapper->delete($userName);
    }

    public function userNameExists($userName): bool
    {
        return $this->userMapper->userNameExists($userName);
    }

    public function userEmailExists($userEmail): bool
    {
        return $this->userMapper->userEmailExists($userEmail);
    }
}