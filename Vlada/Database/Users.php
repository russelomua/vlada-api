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

    public function update(User $user) {
        $sql = "UPDATE users SET ".implode(',', $user->updatesListSQL())." WHERE id = :id";

        $this->database->query($sql, $user->toArraySQL());
        
        return $this->getByID($user->getID());
    }


    /**
     * @param User $user
     * 
     * @return User
     */
    public function Register(User $user) {
        if (!$user->checkUser())
            throw new \Exception("fill_all_fields", ApiErrors::INPUT_ERROR);
        if (!$this->uniqueLogin($user))
            throw new \Exception("user_exist", ApiErrors::INPUT_ERROR);

        

        $sql = "INSERT INTO users (".implode(',', $user->paramsList()).", password) VALUES (".implode(',', $user->paramsListSQL()).", :password)";

        $params = $user->toArray();
        $params['password'] = User::passwordHash($user->getPassword());
        
        $this->database->query($sql, $params);

        $user->setID($this->database->getLastId());
        
        return $user;
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