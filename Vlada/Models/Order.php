<?php

namespace Vlada\Models;

use Vlada\Serialize;

class Order extends Serialize {
    const STATUS_NEW = 'new';
    const STATUS_PAYMENT = 'payment';
    const STATUS_PAYED = 'payed';
    const STATUS_PENDING = 'pending';
    const STATUS_PRINTING = 'printing';
    const STATUS_PENDING_DELIVERY = 'pending_delivery';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_DONE = 'done';

    /**
     * @var number

     */
    public $id;

    public $user_id;

    public $price;

    public $comment;

    public $date;

    public $payment_status;

    public $lat;
    
    public $lon;

    public $status = 'new';

    public function __construct($data)
    {
        $params = ['id','user_id','price', 'comment', 'date', 'payment_status', 'lat', 'lon', 'status'];

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