<?php

namespace Vlada\Modules;

use Vlada\ApiModule;
use Vlada\Database\Users as dbUsers;
use Vlada\Models\User;
use Vlada\ApiResponse;

class Users extends ApiModule {
    /**
     * @var dbUsers
     */
    protected $users;

    /**
     * @var User
     */
    private $user;

    protected function _run() {
        global $user;
        $this->user = $user;
        $this->users = new dbUsers();
    }

    protected function _get() {
        return new ApiResponse(['data' => $this->user->toArray()]);
    }

    protected function _put() {
        $data = (array) $this->request->data;

        unset($data['id']);
        unset($data['rule']);

        error_log(json_encode($this->user->getPassword()));
        
        $this->user->updateValues($data);
        
        error_log(json_encode($this->user->getPassword()));

        $user = $this->users->update($this->user);
        return new ApiResponse(['data' => $user->toArray()]);
    }

    protected function _post() {
        $user = new User((array) $this->request->data);

        $user = $this->users->Register($user);
        return new ApiResponse(['data' => $user->toArray()]);
    }
}

?>