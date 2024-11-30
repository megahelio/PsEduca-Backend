<?php
// file: model/User.php

enum UserRole: string {
    case ADMIN_GLOBAL = 'ADMIN_GLOBAL';
    case GESTOR_CATALOGO = 'GESTOR_CATALOGO';
    case USUARIO_PYP = 'USUARIO_PYP';
}

/**
* Class User
*
* Represents a User
*/
class User
{

    /**
     * The id of this user
     * @var string|null
     */
    private ?string $id;

    /**
     * @var string|null
     */
    private ?string $userName;

    /**
     * The password of the user
     * @var string|null
     * It will be null unless the user is being registered or password is being updated
     */
    private ?string $password;

    /**
     * The role of the user
     * @var UserRole|string|null
     */
    private UserRole|string|null $role;

    /**
     * Full name of the user
     * @var string|null
     */
    private ?string $fullName;

    /**
     * The constructor
     *
     * @param string|null $userName The name of the user
     * @param string|null $passwd The password of the user
     */
    public function __construct(string $id = NULL, string $userName = NULL, string $passwd = NULL, UserRole|string|null $role = NULL, string $fullName = NULL)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->password = $passwd;
        $this->role = $role;
        $this->fullName = $fullName;
    }

    /**
     * Gets the id of this user
     *
     * @return string|null The id of this user
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the id of this user
     *
     * @param string $id The id of this user
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
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
     * Gets the password of this user
     *
     * @return string|null The password of this user
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the password of this user
     *
     * @param string|null $password The password of this user
     * @return void
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * Gets the role of this user.
     * If user comes from the database, it will be a UserRole.
     * If user is being registered, it will be a string (because of format validation).
     */
    public function getRole(): UserRole|string|null
    {
        return $this->role;
    }

    /**
     * Sets the role of this user
     */
    public function setRole(UserRole|string $role): void
    {
        $this->role = $role;
    }

    /**
     * Gets the email of this user
     *
     * @return string|null The email of this user
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * Sets the email of this user
     *
     * @param string $fullName The password of this user
     * @return void
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = empty($fullName) ? null : $fullName;
    }
}
