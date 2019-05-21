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

    private $password;

    protected $login;

    protected $avatar;

    protected $rule;

    protected $email;

    private $secret;

    public function __construct($data)
    {
        $params = ['id','name','surname', 'adress', 'password', 'login', 'avatar', 'rule', 'email', 'secret'];

        foreach ($params as $param) {
            if (!empty($data[$param])) {
                $this->{$param} = $data[$param];
            }
        }

    }

    public function updateValues($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === 'password') {
                    $value = $this->passwordHash($value);
                }
                $this->{$key} = $value;
            }
        }
    }

    static function passwordHash(string $password) {
        return strrev(md5($password))."b3p6f";
    }

    /**
     * @return bool
     */
    public function checkUser() {
        return !empty($this->password) && !empty($this->name) && !empty($this->login) && !empty($this->email);
    }
    
    public function getID() {
        return $this->id;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function isAdmin() {
        return $this->rule == "admin";
    }

    public function getPassword() {
        return $this->password;
    }
}