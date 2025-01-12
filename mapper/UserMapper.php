<?php

require_once(__DIR__."/../core/PDOConnection.php");

/**
* Class UserMapper
*
* Database interface for User entities
*/
class UserMapper {

    private static ?UserMapper $instance = null;

    public static function getInstance(): UserMapper
    {
        if (self::$instance == null) {
            self::$instance = new UserMapper();
        }
        return self::$instance;
    }

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private PDO $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

    /**
     * Gets the user info from the database
     * @param string $userName string the username of the user to get
     * @return User|null the user info
     */
    public function getUserInfoFromUserName(string $userName, bool $bringPassword = false) : ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE nombre_usuario= :nombre_usuario");
        $stmt->execute(array(
            ':nombre_usuario' => $userName
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->userFromRow($data, $bringPassword);
        }
        return null;
    }

    /**
     * Gets the user info from the database
     * @param int $userId the id of the user to get
     * @return User|null the user info
     */
    public function getUserInfo (int $userId, bool $bringPassword = false) : ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id= :id");
        $stmt->execute(array(
            ':id' => $userId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->userFromRow($data, $bringPassword);
        }
        return null;
    }

    public function list(): array
    {
        $stmt = $this->db->query("SELECT * FROM usuarios");
        $usersDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($usersDb as $userDb) {
            $users[] = $this->userFromRow($userDb);
        }
        return $users;
    }

    /**
     * Saves a User into the database
     *
     * @param User $user The user to be saved
     * @return User
     */
	public function save(User $user) : User {
		$stmt = $this->db->prepare("INSERT INTO usuarios (nombre_usuario, nombre_completo, contrasenha, rol)
            values (:nombre_usuario, :nombre_completo, :contrasenha, :rol)");
		$stmt->execute(array(
            ':nombre_usuario' => $user->getUserName(),
            ':nombre_completo' => $user->getFullName(),
            ':contrasenha' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
            ':rol' => $user->getRole()->name
        ));
        $user->setId($this->db->lastInsertId());
        return $user;
	}

    public function edit(User $user): void {

        $sql = "UPDATE usuarios SET 
                nombre_usuario = :nombre_usuario, 
                nombre_completo = :nombre_completo, 
                rol = :rol";

        $params = [
            ':id' => $user->getId(),
            ':nombre_usuario' => $user->getUserName(),
            ':nombre_completo' => $user->getFullName(),
            ':rol' => $user->getRole()->name
        ];

        // Agregar contraseña si está definida y no es nula
        if ($user->getPassword()) {
            $sql .= ", contrasenha = :contrasenha";
            $params[':contrasenha'] = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }


    /**
     * Deletes a user from the database
     * @param $userId string the username of the user to get
     * @return bool
     */
    public function delete(string $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->execute(array($userId));

        return $stmt->rowCount() == 1;
    }

	/**
	* Checks if a given username is already in the database
	*
	* @param string $userName the username to check
	* @return boolean true if the username exists, false otherwise
	*/
	public function userNameExists(string $userName): bool
    {
		$stmt = $this->db->prepare("SELECT count(*) FROM usuarios where nombre_usuario=?");
		$stmt->execute(array($userName));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
		return false;
	}

    public function userNameExistsExceptSelf(string $userName, ?string $userId = null): bool
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM usuarios where nombre_usuario=? AND id!=?");
        $stmt->execute(array($userName, $userId));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks if a given email is already in the database
     * @param int $userId the ID to check
     * @return bool true if the email exists, false otherwise
     */
    public function userExists(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM usuarios where id=?");
        $stmt->execute(array($userId));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
        return false;
    }

    private function userFromRow($row, bool $bringPassword = false): User {
        return new User(
            $row['id'],
            $row['nombre_usuario'],
            $bringPassword? $row['contrasenha'] : null,
            UserRole::from($row['rol']),
            $row['nombre_completo']);
    }
}
