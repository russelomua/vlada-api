<?php

namespace Vlada\Models;

use Vlada\Serialize;

class File extends Serialize {
    /**
     * @var number
     */
    public $id;

    public $user_id;

    public $filename;

    public $route;

    public $status;

    public $hash;

    public function __construct($data)
    {
        $params = ['id','user_id','filename', 'route', 'status', 'hash'];

        foreach ($params as $param) {
            if (!empty($data[$param])) {
                $this->{$param} = $data[$param];
            }
        }

    }

    public function setID($id) {
        $this->id = $id;
    }
}