<?php
namespace Vlada\Models;

use Vlada\Serialize;

class DronQueue extends Serialize {
    /**
     * Metr per second
     */
    const DRON_SPEED = 100;
    const DRON_LANDING_TIME = 20;

    const OFFICE = [50.000171, 36.249778];

    public $id;

    public $dron_id = 1;

    public $order_id;

    public $start;

    public $finish;

    function __construct($data = []) {
        $params = ['id','dron_id','order_id', 'start', 'finish'];

        foreach ($params as $param) {
            if (!empty($data[$param])) {
                $this->{$param} = $data[$param];
            }
        }
    }

    /**
     * Optimized algorithm from http://www.codexworld.com
     *
     * @param float $latitudeFrom
     * @param float $longitudeFrom
     * @param float $latitudeTo
     * @param float $longitudeTo
     *
     * @return float [m]
     */
    public static function getDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
        $rad = M_PI / 180;
        //Calculate distance from latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin($latitudeFrom * $rad) 
            * sin($latitudeTo * $rad) +  cos($latitudeFrom * $rad)
            * cos($latitudeTo * $rad) * cos($theta * $rad);

        return acos($dist) / $rad * 60 * 1853;
    }

    /**
     * @return int [seconds]
     */
    public static function getTime($distance) {
        return round(($distance / self::DRON_SPEED) + self::DRON_LANDING_TIME);
    }
}