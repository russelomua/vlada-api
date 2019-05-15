<?php

namespace Vlada\Database;

use Vlada\ApiErrors;
use Vlada\Models\File;
use Vlada\Models\User;

class Files extends Database {
    /**
     * @return File[]
     */
    public function Get(User $user, $order_id = null) {
        $return = [];
        $sql = "SELECT * FROM files WHERE order_id = :order_id";
        $params = [
            'order_id' => $order_id
        ];

        // if ($order_id) {
        //     $sql .= " AND order_id = :order_id";
        //     $params['order_id'] = $order_id;
        // }

        $result = $this->database->query($sql, $params);

        while($data = $result->fetch())
            $return[] = new File($data);

        return $return;
    }

    public function Upload(File $file) {
        $sql = "INSERT INTO files (".implode(',', $file->paramsList()).") VALUES (".implode(',', $file->paramsListSQL()).")";

        $this->database->query($sql, $file->toArray());
        
        $file->setID($this->database->getLastId());

        return $file;
    }

    public function Remove(File $file) {
        $sql = "DELETE FROM files WHERE id = :id";

        try{
            $this->database->query($sql, ['id'=>$file->id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findByID($id) {
        $sql = "SELECT * FROM files WHERE id = :id";

        $query = $this->database->query($sql, ['id'=>$id]);

        if ($query->rowCount() == 1)
            return new File($query->fetch());
        return null;
    }

    /**
     * Search file by hash
     * @param string $hash
     * @param int|null $user_id
     * 
     * @return bool
     */
    public function findByHash($hash, $order_id = null) {
        $sql = "SELECT * FROM files WHERE hash = :hash";
        $params = ['hash' => $hash];
        if (!empty($order_id) && is_numeric($order_id)) {
            $sql .= " AND order_id = :order_id";
            $params['order_id'] = $order_id;
        }

        $result = $this->database->query($sql, $params);

        if ($result->rowCount() > 0)
            return true;

        return false;
    }
}

?>