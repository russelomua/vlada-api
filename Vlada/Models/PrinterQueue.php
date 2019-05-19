<?php
namespace Vlada\Models;

use Vlada\Serialize;

class PrinterQueue extends Serialize {
    public $id;
    public $printer_id = 1;
    public $file_id;
    public $start;
    public $finish;

    function __construct($data = []) {
        $params = ['id','printer_id','file_id', 'start', 'finish'];

        foreach ($params as $param) {
            if (!empty($data[$param])) {
                $this->{$param} = $data[$param];
            }
        }
    }
}

?>