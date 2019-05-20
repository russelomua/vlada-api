<?php
use Vlada\MySQL;
use Vlada\Models\Order;
use Vlada\Models\File;
use Vlada\Database\Orders;
use Vlada\Database\Files;
use Vlada\Database\Printers;

include_once("./config.php");
include_once(ROOT_DIR."/vendor/autoload.php");
include_once(ROOT_DIR."/autoload.php");
// connection
$database = new MySQL(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);

$dbOrders = new Orders();
$dbFiles = new Files();
$dbPrinters = new Printers();

/**
 * Process pediding orders
 */
$orders = $dbOrders->getOrdersByStatus(Order::STATUS_PENDING);
echo "Found ".count($orders)." Pending orders\n";

foreach ($orders as $order) {
    $order->status = Order::STATUS_PAYMENT;
    $order->price = 0;

    $files = $dbFiles->Get($order->id);
    foreach ($files as $file) {
        $price = $file->calcPrice();
        $order->price += $price;
        echo "File ".$file->id." = ".$price." UAH\n";
    }

    echo "Price ".$order->price." to order ".$order->id."\n";
    $dbOrders->updateOrder($order);
}

$orders = $dbOrders->getOrdersByStatus(Order::STATUS_PAYED);
echo "Found ".count($orders)." Payed orders\n";

foreach ($orders as $order) {
    $order->status = Order::STATUS_PRINTING;

    $files = $dbFiles->Get($order->id);
    foreach ($files as $file) {
        $dbPrinters->addQueue($file);
        echo "File ".$file->id." Added to queue\n";
    }

    $dbOrders->updateOrder($order);
}

$orders = $dbOrders->getOrdersByStatus(Order::STATUS_PRINTING);
echo "Found ".count($orders)." printing orders\n";

foreach ($orders as $order) {
    $files = $dbFiles->Get($order->id);
    $all = true;
    foreach ($files as $file) {
        if ($file->status !== File::STATUS_DONE) {
            $all = false;
        }
    }
    if ($all) {
        $order->status = Order::STATUS_ENTER_LOCATION;
        $dbOrders->updateOrder($order);
    }
}

?>