<?php
// file: model/UserMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
* Class UserMapper
*
* Database interface for User entities
*/
class UserMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private PDO $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

//    /**
//     * Checks if a given pair of username/password exists in the database and returns the User object
//     *
//     * @param string $userName the username
//     * @param string $password the password
//     * @return User|null The user
//     */
//    public function getValidUser(string $userName, string $password): ?User
//    {
//        $stmt = $this->db->prepare("SELECT * FROM usuarios where nombre_usuario=? and contrasenha=?");
//        $stmt->execute(array($userName, $password));
//
//        $data = $stmt->fetch(PDO::FETCH_ASSOC);
//
//        if ($data) {
//            return $this->userFromRow($data);
//        }
//        return null;
//    }

    /**
     * Gets the user info from the database
     * @param string $userName string the username of the user to get
     * @return User|null the user info
     */
    public function getUserInfoFromUserName(string $userName) : ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE nombre_usuario= :nombre_usuario");
        $stmt->execute(array(
            ':nombre_usuario' => $userName
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->userFromRow($data);
        }
        return null;
    }

    /**
     * Gets the user info from the database
     * @param $userId string the username of the user to get
     * @return User the user info
     */
    public function getUserInfo (int $userId) : ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id= :id");
        $stmt->execute(array(
            ':id' => $userId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->userFromRow($data);
        }
        return null;
    }

	/**
	* Saves a User into the database
	*
	* @param User $user The user to be saved
	* @return void
	*@throws PDOException if a database error occurs
	*/
	public function save(User $user) : void {
		$stmt = $this->db->prepare("INSERT INTO usuarios (nombre_usuario, nombre_completo, contrasenha, rol)
            values (:nombre_usuario, :nombre_completo, :contrasenha, :rol)");
		$stmt->execute(array(
            ':nombre_usuario' => $user->getUserName(),
            ':nombre_completo' => $user->getFullName(),
            ':contrasenha' => $user->getPassword(),
            ':rol' => $user->getRole()->name
        ));
	}

    /**
     * Deletes a user from the database
     * @param $userName string the username of the user to get
     * @return void
     */
    public function delete(string $userName): void
    {
        $stmt = $this->db->prepare("DELETE FROM User WHERE user_name=?");
        $stmt->execute(array($userName));
    }

	/**
	* Checks if a given username is already in the database
	*
	* @param string $userName the username to check
	* @return boolean true if the username exists, false otherwise
	*/
	public function userNameExists(string $userName): bool
    {
		$stmt = $this->db->prepare("SELECT count(*) FROM User where user_name=?");
		$stmt->execute(array($userName));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
		return false;
	}

    /**
     * Checks if a given email is already in the database
     * @param string $userEmail the email to check
     * @return bool true if the email exists, false otherwise
     */
    public function userEmailExists(string $userEmail): bool
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM User where user_email=?");
        $stmt->execute(array($userEmail));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
        return false;
    }

    private function userFromRow($row): User {
        return new User(
            $row['id'],
            $row['nombre_usuario'],
            $row['contrasenha'],
            UserRole::from($row['rol']),
            $row['nombre_completo']);
    }
}
