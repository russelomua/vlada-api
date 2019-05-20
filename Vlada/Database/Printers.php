<?php
namespace Vlada\Database;

use Vlada\Models\File;
use Vlada\Models\PrinterQueue;

class Printers extends Database {
    function addQueue(File $file) {
        $start = $this->getQueueLast();
        $start += 60;
        $finish = $start + 60*5;

        $queue = new PrinterQueue([
            'file_id' => $file->id,
            'start' => date("Y-m-d H:i:s", $start),
            'finish' => date("Y-m-d H:i:s", $finish),
        ]);
        $sql = "INSERT INTO printers_queue (".implode(',', $queue->paramsList()).") VALUES (".implode(',', $queue->paramsListSQL()).")";

        $this->database->query($sql, $queue->toArray());
    }

    function getQueueLast() {
        $sql = "SELECT finish FROM printers_queue ORDER BY finish DESC LIMIT 1";
        $now = time();
        $query = $this->database->query($sql);
        if ($query->rowCount() === 0) {
            return $now;
        } else {
            $strDate = $query->fetchColumn();
            $last = strtotime($strDate);
            echo "now ".$now." > last ".$strDate."  ".$last."\n";
            return ($now > $last ? $now : $last);
        }
    }
}