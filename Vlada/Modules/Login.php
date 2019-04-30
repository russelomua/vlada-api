<?php
namespace Vlada\Modules;

use Vlada\ApiModule;
use Vlada\ApiResponse;
use Vlada\ApiErrors;
use Vlada\Models\User;

class Login extends ApiModule {
    protected function _run() {

    }

    protected function _post() {
        if (empty($this->request->data->login) || empty($this->request->data->password))
            throw new \Exception("Empty Login or Password", ApiErrors::INPUT_ERROR);

        $query = "SELECT * FROM users WHERE login = :login AND password = :password";

        $result = $this->database->query($query, [
            "login" => $this->request->data->login,
            "password" => User::passwordHash($this->request->data->password),
        ]);
        
        if ($result->rowCount() != 1)
            throw new \Exception("Wrong Login or Password", ApiErrors::INPUT_ERROR);

        $user = new User($result->fetch());

        return new ApiResponse(["data"=>$user->toArray()]);
    }
}

?>