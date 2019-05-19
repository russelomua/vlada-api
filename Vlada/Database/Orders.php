<?php

namespace Vlada\Database;

use Vlada\ApiErrors;
use Vlada\Models\Order;
use Vlada\Models\User;

class Orders extends Database {
    /**
     * @return Order
     */
    public function getByID($order_id) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $result = $this->database->query($sql, [
            'id' => $order_id
        ]);

        if ($result->rowCount() == 1)
            return new Order($result->fetch());
    }

    /**
     * @return Order[]
     */
    public function getAll(User $user) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id";
        $result = $this->database->query($sql, [
            'user_id' => $user->getID()
        ]);

        while($data = $result->fetch())
            $return[] = new Order($data);

        return $return;
    }

    public function createOrder(Order $order) {
        $sql = "INSERT INTO orders (".implode(',', $order->paramsList()).") VALUES (".implode(',', $order->paramsListSQL()).")";

        $this->database->query($sql, $order->toArray());

        $order->id = $this->database->getLastId();
        
        return $this->getByID($order->id);
    }

    public function removeOrder(Order $order) {
        $sql = "DELETE FROM orders WHERE user_id = :user_id AND id = :order_id";

        $this->database->query($sql, [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ]);

        $sql = "DELETE FROM files WHERE order_id = :order_id";

        $this->database->query($sql, [
            'order_id' => $order->id
        ]);
    }

    public function updateOrder(Order $order) {
        $sql = "UPDATE orders SET ".implode(',', $order->updatesListSQL())." WHERE id = :id";

        $this->database->query($sql, $order->toArray());
        
        return $this->getByID($order->id);
    }

    public function getOrdersByStatus(string $status) {
        $sql = "SELECT * FROM orders WHERE status = :status ORDER BY id ASC";

        $result = $this->database->query($sql, [
            'status' => $status
        ]);

        while($data = $result->fetch())
            $return[] = new Order($data);

        return $return;
    }
}