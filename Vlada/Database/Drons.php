<?php
namespace Vlada\Database;

use Vlada\Models\Order;
use Vlada\Models\DronQueue;

class Drons extends Database {
    function addQueue(Order $order) {
        $start = $this->getQueueLast();
        $start += 60;

        $distance = DronQueue::getDistance(DronQueue::OFFICE[0], DronQueue::OFFICE[1], $order->lat, $order->lon);        
        $finish = $start + DronQueue::getTime($distance);

        $queue = new DronQueue([
            'order_id' => $order->id,
            'start' => date("Y-m-d H:i:s", $start),
            'finish' => date("Y-m-d H:i:s", $finish),
        ]);

        $sql = "INSERT INTO drones_queue (".implode(',', $queue->paramsList()).") VALUES (".implode(',', $queue->paramsListSQL()).")";

        $this->database->query($sql, $queue->toArraySQL());
    }

    function getQueueLast() {
        $sql = "SELECT finish FROM drones_queue ORDER BY finish DESC LIMIT 1";
        $now = time();
        $query = $this->database->query($sql);
        if ($query->rowCount() === 0) {
            return $now;
        } else {
            $strDate = $query->fetchColumn();
            $last = strtotime($strDate);
            return ($now > $last ? $now : $last);
        }
    }

    function checkStatus($order_id) {
        $sql = "SELECT id FROM drones_queue WHERE order_id = :order_id AND NOW() >= finish ORDER BY finish DESC LIMIT 1";
        $query = $this->database->query($sql, [
            'order_id' => $order_id
        ]);
        return ($query->rowCount() === 1);
    }

    function getDelivery($order_id) {
        $sql = "SELECT * FROM drones_queue WHERE order_id = :order_id ORDER BY finish DESC LIMIT 1";
        $query = $this->database->query($sql, [
            'order_id' => $order_id
        ]);
        return new DronQueue($query->fetch());
    }
}