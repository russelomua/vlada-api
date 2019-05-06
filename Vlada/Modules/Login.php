<?php
namespace Vlada\Modules;

use Vlada\ApiModule;
use Vlada\ApiResponse;
use Vlada\ApiErrors;
use Vlada\Models\User;
use Vlada\Database\Users;
use Vlada\Models\Session;

class Login extends ApiModule {
    /**
     * @var Users
     */
    private $users;

    protected function _run() {
        $this->users = new Users();
    }

    protected function _post() {
        $user = $this->users->Login($this->request->data->login, $this->request->data->password);

        $session = new Session($user->getID());

        $this->users->UpdateSecret($session, $user);

        return new ApiResponse([
            "data"=> $session->toArray()
        ]);
    }
}

?>