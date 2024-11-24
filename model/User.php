<?php
// file: model/User.php

require_once(__DIR__."/../core/ValidationException.php");

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
     * @var int|null
     */
    private ?int $id;

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
     * The role of the user
     * @var UserRole|null
     */
    private ?UserRole $role;

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
    public function __construct(int $id = NULL, string $userName = NULL, string $passwd = NULL, UserRole $role = NULL, string $fullName = NULL)
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
     * @return int|null The id of this user
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of this user
     *
     * @param int $id The id of this user
     * @return void
     */
    public function setId(int $id): void
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
     * @param string $password The password of this user
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Gets the role of this user
     */
    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    /**
     * Sets the role of this user
     */
    public function setRole(UserRole $role): void
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
