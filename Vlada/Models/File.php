<?php

namespace Vlada\Models;

use Vlada\Serialize;

class File extends Serialize {
    const UPLOAD_DIR = 'files';

    const ALLOWED_EXT = [
        'txt', 'rtf', // simple
        'pdf', // adobe
        'doc', 'xls', 'xlsx', 'docx', 'docx', // MSOffice
        'odt', 'fodt', 'ods', 'fods', // OpenOffice
    ];

    const PRICE_COEFICIENT = [
        'txt' => 40, 'rtf' => 15, // simple
        'pdf' => 10, // adobe
        'doc' => 15, 'xls' => 15, 'xlsx' => 15, 'docx' => 15, 'docx' => 15, // MSOffice
        'odt' => 15, 'fodt' => 15, 'ods' => 15, 'fods' => 15, // OpenOffice
    ];

    /**
     * @var number
     */
    public $id;

    public $order_id;

    public $filename;

    public $route;

    public $status;

    public $hash;

    public function __construct($data)
    {
        $params = ['id','order_id','filename', 'route', 'status', 'hash'];

        foreach ($params as $param) {
            if (!empty($data[$param])) {
                $this->{$param} = $data[$param];
            }
        }

    }

    public function setID($id) {
        $this->id = $id;
    }

    /**
     * @return int price
     */
    public function calcPrice() {
        // Mega bytes
        $size = filesize(ROOT_DIR.$this->route)/1024/32;

        $ext = pathinfo($this->filename, PATHINFO_EXTENSION);
        return round($size * self::PRICE_COEFICIENT[$ext]);
    }
}