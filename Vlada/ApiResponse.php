<?php

namespace Vlada;

class ApiResponse {
    public $data;
    public $status = 200;

    /**
     * @param Array[data,status] $data
     */
    public function __construct(Array $data = []) {
        if (array_key_exists('data', $data))
            $this->data = $data['data'];
        if (array_key_exists('status', $data))
            $this->status = $data['status'];
    }
}

?>