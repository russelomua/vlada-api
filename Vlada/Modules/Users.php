<?php

namespace Vlada\Modules;

use Vlada\ApiModule;
use Vlada\Database\Users as dbUsers;
use Vlada\Models\User;

class Users extends ApiModule {
    /**
     * @var dbUsers
     */
    protected $users;

    protected function _run() {
        $this->users = new dbUsers();
    }

    protected function _get() {
        
    }
    protected function _post() {
        $user = new User((array) $this->request->data);

        $user = $this->users->Register($user);
    }
}

?>