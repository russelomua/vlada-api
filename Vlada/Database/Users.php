<?php

namespace Vlada\Database;

use Vlada\Models\User;
use Vlada\ApiErrors;
use Vlada\Models\Session;

class Users extends Database {
    public function getByID($user_id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->database->query($sql, [
            'id' => $user_id
        ]);

        if ($result->rowCount() == 1)
            return new User($result->fetch());
    }

    public function Login($login, $password) {
        if (empty($login) || empty($password))
            throw new \Exception("Пустые Логин или Пароль", ApiErrors::INPUT_ERROR);

        $query = "SELECT * FROM users WHERE login = :login AND password = :password";

        $result = $this->database->query($query, [
            "login" => $login,
            "password" => User::passwordHash($password),
        ]);

        if ($result->rowCount() != 1)
            throw new \Exception("Неверная комбинация Логина и Пароля", ApiErrors::INPUT_ERROR);

        return new User($result->fetch());
    }

    public function UpdateSecret(Session $session, User $user) {
        $sql = "UPDATE users SET secret = :secret WHERE id = :id";

        $this->database->query($sql, [
            'secret' => $session->GetSecret(),
            'id' => $user->getID(),
        ]);
    }

    /**
     * @param User $user
     * 
     * @return User
     */
    public function Register(User $user) {
        if (!$this->checkUser($user))
            throw new \Exception("Fill all fields", ApiErrors::INPUT_ERROR);
        if ($this->uniqueLogin($user))
            throw new \Exception("User exist", ApiErrors::INPUT_ERROR);

        $sql = "INSERT INTO users (".implode(',', $user->paramsList()).") VALUES (".implode(',', $user->paramsListSQL()).")";

        $this->database->query($sql, $user->toArray());

        $user->setID($this->database->getLastId());
        
        return $user;
    }

    /**
     * @param User $user
     * 
     * @return bool
     */
    public function checkUser(User $user) {
        return !empty($user->name) && !empty($user->login) && !empty($user->email);
    }
    
    /**
     * @param User $user
     * 
     * @return bool
     */
    public function uniqueLogin(User $user) {
        $query = "SELECT * FROM users WHERE login = :login";

        $result = $this->database->query($query, [
            "login" => $user->getLogin(),
        ]);

        return ($result->rowCount() == 0);
    }
}

?>