<?php

namespace Vlada\Models;

use Vlada\Serialize;

class User extends Serialize {
    /**
     * @var number
     */
    protected $id;

    protected $name;

    protected $surname;

    protected $adress;

    protected $login;

    protected $avatar;

    protected $rule;

    protected $email;

    public function __construct($data)
    {
        $params = ['id','name','surname', 'adress', 'login', 'avatar', 'rule', 'email'];

        foreach ($params as $param) {
            if (!empty($data[$param])) {
                $this->{$param} = $data[$param];
            }
        }

    }

    static function passwordHash(string $password) {
        return strrev(md5($password))."b3p6f";
    }
}