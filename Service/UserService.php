<?php

require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../mapper/UserMapper.php";
require_once __DIR__ . "/../core/Validator.php";

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

    public function get($userName) : User
    {
        return $this->userMapper->getUserInfo($userName);
    }

    /**
     * @throws ValidationException
     */
    public function add($username, $passwd, $email): void
    {

        $user = new User($username, $passwd, $email);

        $format_errors = $this->validator->validate($user);

        if (sizeof($format_errors) > 0) {
            throw new ValidationException($format_errors);
        }

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

    public function isValidUser($userName, $password): bool
    {
        return $this->userMapper->isValidUser($userName, $password);
    }
}