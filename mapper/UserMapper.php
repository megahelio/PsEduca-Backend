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
//		$this->db = PDOConnection::getInstance();
	}

	/**
	* Saves a User into the database
	*
	* @param User $user The user to be saved
	* @return void
	*@throws PDOException if a database error occurs
	*/
	public function save(User $user) : void {
		$stmt = $this->db->prepare("INSERT INTO User (user_name, user_password, user_email) values (?,?,?)");
		$stmt->execute(array($user->getUserName(), $user->getPassword(), $user->getEmail()));
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
     * Gets the user info from the database
     * @param $userName string the username of the user to get
     * @return User the user info
     */
    public function getUserInfo(string $userName) : User
    {
        $stmt = $this->db->prepare("SELECT user_email FROM User WHERE user_name=?");
        $stmt->execute(array($userName));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User(
            $userName,
            null,
            $data['user_email']
        );
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

	/**
	* Checks if a given pair of username/password exists in the database
	*
	* @param string $username the username
	* @param string $passwd the password
	* @return boolean true the username/passwrod exists, false otherwise.
	*/
	public function isValidUser($username, $passwd): bool
    {
		$stmt = $this->db->prepare("SELECT count(*) FROM User where user_name=? and user_password=?");
		$stmt->execute(array($username, $passwd));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
		return false;
	}
}
