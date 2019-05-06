<?php

namespace Vlada\Database;

use Vlada\ApiErrors;
use Vlada\Models\File;
use Vlada\Models\User;

class Files extends Database {
    /**
     * @return File[]
     */
    public function Get(User $user) {
        $return = [];
        $sql = "SELECT * FROM files WHERE user_id = :user_id";

        $result = $this->database->query($sql, [
            'user_id' => $user->getID()
        ]);

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
    public function findByHash($hash, $user_id = null) {
        $sql = "SELECT * FROM files WHERE hash = :hash";
        $params = ['hash' => $hash];
        if (!empty($user_id) && is_numeric($user_id)) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $user_id;
        }

        $result = $this->database->query($sql, $params);

        if ($result->rowCount() > 0)
            return true;

        return false;
    }
}

?>