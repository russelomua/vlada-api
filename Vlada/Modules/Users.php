<?php

namespace Vlada\Modules;

use LoraApi\ApiModule;

class Users extends ApiModule {
    protected function _run() {

    }

    protected function _get() {
        
    }
    protected function _post() {
        $user = new User($this->request->data);
    }
}

?>