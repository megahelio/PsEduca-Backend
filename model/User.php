<?php
// file: model/User.php

require_once(__DIR__."/../core/ValidationException.php");

/**
* Class User
*
* Represents a User
*/
class User
{

    /**
     * @var string|null
     * @NotNull
     * @Regex (
     *     pattern="/^[a-zA-Z0-9]{6,}$/",
     * )
     */
    private ?string $userName;

    /**
     * The password of the user
     * @var string|null
     * @NotNull
     * @Regex(
     *     pattern="/^[a-zA-Z0-9]{6,}$/",
     *     message="El password debe tener al menos 6 caracteres alfanuméricos."
     * )
     * It will be null unless the user is being registered or password is being updated
     */
    private ?string $password;

    /**
     * The email of the user
     * @var string|null
//
     * @Regex (
     *     pattern="/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
     * )
     */
    private ?string $email;

    /**
     * The constructor
     *
     * @param string|null $userName The name of the user
     * @param string|null $passwd The password of the user
     */
    public function __construct(string $userName = NULL, string $passwd = NULL, string $email = NULL)
    {
        $this->userName = $userName;
        $this->password = $passwd;
        $this->email = $email;
    }

    /**
     * Gets the username of this user
     *
     * @return string|null The username of this user
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * Sets the username of this user
     *
     * @param string $userName The username of this user
     * @return void
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * Gets the password of this user.
     * @return string|null The password of this user
     */
	public function getPassword(): ?string
    {
		return $this->password;
	}

    /**
     * Sets the password of this user
     *
     * @param string $password The password of this user
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Gets the email of this user
     *
     * @return string|null The email of this user
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email of this user
     *
     * @param string $email The password of this user
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = empty($email) ? null : $email;
    }
}
