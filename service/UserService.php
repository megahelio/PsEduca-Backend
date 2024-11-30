<?php

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

    public function __construct() {
        $this->userMapper = UserMapper::getInstance();
    }

    /**
     * @param string|null $userName the username sent by the still anonymous user
     * @param string|null $userPassword the password sent by the still anonymous user
     * @return array an array containing the user, the JWT token, and the expiration date of the token
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function login(?string $userName, ?string $userPassword): array
    {
        $user = new User(null, $userName, $userPassword, null, null);
        Validator::validate($user, Action::LOGIN);

        // Get the user info from the database
        $user = $this->userMapper->getUserInfoFromUserName($userName, true);

        if ($user != null) {

            if (!password_verify($userPassword, $user->getPassword())) {
                // No damos detalles sobre si el usuario o la contraseña son incorrectos, se trata del mismo modo.
                throw new NotFoundException();
            }

            $payload = [
//                'iss' => SERVER_URL,
                'sub' => $user->getId(),
                'iat' => time(),
                'exp' => time() + (86400 * 30), // Los tokens expiran en 1 día
                'role' => $user->getRole()->name,
            ];

            $jwt_instance = new JWT();
            $jwt_token = $jwt_instance->generate($payload);

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
     * @throws ValidationException
     * @throws ReflectionException
     */
    public function add(?string $username, ?string $fullName, ?string $password, ?string $strRole): User
    {

        $user = new User(null, $username, $password, $strRole, $fullName);

        Validator::validate($user, Action::ADD);

        return $this->userMapper->save($user);
    }

    /**
     * @throws ValidationException
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function edit(?string $id, ?string $userName, ?string $fullName, ?string $password, ?string $strRole): User
    {
        $user = $this->get($id);

        // Solo se modifican los campos que se envían. Si no se envía un campo, se mantiene el valor actual.
        if ($userName != null) $user->setUserName($userName);
        if ($fullName != null) $user->setFullName($fullName);
        $user->setPassword($password); // Salvo para auth, este campo es nulo, por lo que nos podemos ahorrar la comprobación
        if ($strRole != null) $user->setRole($strRole);

        Validator::validate($user, Action::EDIT);

        $this->userMapper->edit($user);

        return $user;
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function delete($userId): void
    {
        $user = new User($userId, null, null,null,null);
        Validator::validate($user, Action::DELETE);

        if (!$this->userMapper->delete($userId)) {
            throw new NotFoundException();
        }
    }

    /**
     * @param string|null $userId el id del usuario a obtener
     * @return User
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function get(?string $userId) : User
    {
        $user = new User($userId, null, null,null,null);
        Validator::validate($user, Action::GET);

        $user = $this->userMapper->getUserInfo($userId);

        if ($user != null) {
            return $user;
        } else {
            throw new NotFoundException();
        }
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function list(): array
    {
        Validator::validate(new User(), Action::LIST);
        return $this->userMapper->list();
    }
}