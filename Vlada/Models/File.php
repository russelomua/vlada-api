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
}