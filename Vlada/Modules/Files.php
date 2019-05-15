<?php

namespace Vlada\Modules;

use Vlada\ApiModule;
use Vlada\Database\Files as dbFiles;
use Vlada\Models\File;
use Vlada\Models\User;
use Vlada\ApiResponse;
use Vlada\ApiErrors;

class Files extends ApiModule {
    /**
     * @var dbFiles
     */
    protected $files;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var int $fileID
     */
    private $fileID;

    protected function _run() {
        global $user;

        if (!($user instanceof User))
            throw new \Exception("Сессия завершена", ApiErrors::AUTH_ERROR);
            
        $this->user = $user;
        $this->files = new dbFiles();

        $this->fileID = $this->request->action_list[0];
    }

    protected function _get() {
        $files = $this->files->Get($this->user);

        return new ApiResponse(["data" => $files]);
    }

    /**
     * [upload] => Array
     * (
     *      [name] => LoadOption.ini
     *      [type] => application/octet-stream
     *      [tmp_name] => E:\xampp\tmp\php7DCD.tmp
     *      [error] => 0
     *      [size] => 43
     * )
     */
    protected function _post() {
        if ( count( $_FILES ) == 0 )
            throw new \Exception("Ошибка загрузки", ApiErrors::INPUT_ERROR);

        $file = new File([
            "filename" => basename($_FILES['upload']['name']),
            "status" => "pending",
            "hash" => sha1_file($_FILES['upload']['tmp_name']),
            "order_id" => $this->user->getID(),
        ]);
                
        $ext = pathinfo($file->filename, PATHINFO_EXTENSION);

        if (!in_array($ext, self::ALLOWED_EXT)) 
            throw new \Exception("Не верный формат файла", ApiErrors::INPUT_ERROR);

        if ($this->files->findByHash($file->hash, $this->user->getID()))
            throw new \Exception("Этот файл уже загружен", ApiErrors::INPUT_ERROR);

        $file->route = implode(DIRECTORY_SEPARATOR, ["", self::UPLOAD_DIR, $this->user->getID()."_".$file->hash.".".$ext]);
        if (move_uploaded_file($_FILES['upload']['tmp_name'], ROOT_DIR.$file->route)) {
            $this->files->Upload($file);
        } else 
            throw new \Exception("Произошла ошибка на сервере", ApiErrors::INPUT_ERROR);

        return new ApiResponse(["data" => (array) $file]);
    }

    protected function _delete() {
        if (empty($this->fileID))
            parent::_delete();

        $file = $this->files->findByID($this->fileID);

        if (empty($file))
            throw new \Exception("Файл не найден", ApiErrors::NOTFOUND_ERROR);

        if ($file->user_id !== $this->user->getID() && !$this->user->isAdmin())
            throw new \Exception("Этот файл не ваш", ApiErrors::RIGHT_ERROR);

        $this->files->Remove($file);

        return new ApiResponse(["data" => (array) $file]);
    }
}

?>