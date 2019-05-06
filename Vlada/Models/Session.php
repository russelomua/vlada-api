<?php

namespace Vlada\Models;

use Vlada\Serialize;
use Firebase\JWT\JWT;

class Session extends Serialize {
    private $user_id;
    private $exp;

    protected $token;
    protected $secret;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->exp = time() + API_TOKEN_LIFETIME*60;

        $this->token = $this->GenerateToken();
        $this->secret = $this->GenerateSecret(); 
    }

    private function GenerateToken() {
        return JWT::encode([
            'user_id' => $this->user_id,
            'exp' => $this->exp,
        ], API_SECRET);
    }

    private function GenerateSecret() {
        $length = 64;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function GetSecret() {
        return $this->secret;
    }
    
    public function GetToken() {
        return $this->token;
    }
}

?>