<?php
namespace Vlada\Modules;

use Vlada\ApiModule;
use Vlada\ApiResponse;
use Vlada\ApiErrors;
use Vlada\Models\User;
use Vlada\Models\Order;
use Vlada\Models\File;
use Vlada\Database\Orders as dbOrders;
use Vlada\Database\Files;
use Vlada\Database\Drons;

class Orders extends ApiModule {
    /**
     * @var dbOrders
     */
    private $orders;

    /**
     * @var Files
     */
    private $files;

    private $order_id;

    private $subAction;

    /**
     * @var User
     */
    private $user;

    protected function _run() {
        global $user;

        if (!($user instanceof User))
            throw new \Exception("Сессия завершена", ApiErrors::AUTH_ERROR);

        $this->orders = new dbOrders();
        $this->files = new Files();
        $this->user = $user;

        $this->order_id = $this->request->action_list[0];

        if (count($this->request->action_list) > 0);
            $this->subAction = $this->request->action_list[1];
    }

    protected function _get() {
        if (empty($this->order_id)) {
            $orders = $this->orders->getAll($this->user);

            return new ApiResponse(["data" => $orders]);
        }
            

        switch ($this->subAction) {
            default:
                $order = $this->orders->getByID($this->order_id);

                return new ApiResponse([
                    'data' => $order->toArray()
                ]);
                break;
            case "files":
                $files = $this->files->Get($this->order_id);

                return new ApiResponse(["data" => $files]);
                break;
            case "delivery":
                $drons = new Drons();

                $queue = $drons->getDelivery($this->order_id);

                return new ApiResponse(['data' => $queue->toArray()]);
                break;
        }
    }

    protected function _post() {
        if (empty($this->order_id)) {
            $order = new Order(['user_id' => $this->user->getID()]);
    
            $order = $this->orders->createOrder($order);
    
            return new ApiResponse([
                "data"=> $order->toArray()
            ]);
        }

        switch ($this->subAction) {
            case "files":
                if ( count( $_FILES ) == 0 )
                    throw new \Exception("Ошибка загрузки", ApiErrors::INPUT_ERROR);

                $file = new File([
                    "filename" => basename($_FILES['upload']['name']),
                    "status" => "pending",
                    "hash" => sha1_file($_FILES['upload']['tmp_name']),
                    "order_id" => $this->order_id,
                ]);
                        
                $ext = pathinfo($file->filename, PATHINFO_EXTENSION);

                if (!in_array($ext, File::ALLOWED_EXT)) 
                    throw new \Exception("Не верный формат файла", ApiErrors::INPUT_ERROR);

                if ($this->files->findByHash($file->hash, $this->order_id))
                    throw new \Exception("Этот файл уже загружен", ApiErrors::INPUT_ERROR);

                $file->route = implode(DIRECTORY_SEPARATOR, ["", File::UPLOAD_DIR, $this->user->getID()."_".$file->hash.".".$ext]);
                if (move_uploaded_file($_FILES['upload']['tmp_name'], ROOT_DIR.$file->route)) {
                    $this->files->Upload($file);
                } else 
                    throw new \Exception("Произошла ошибка на сервере", ApiErrors::INPUT_ERROR);

                return new ApiResponse(["data" => (array) $file]);
                break;
        }
    }

    protected function _put() {
        $order = new Order((array) $this->request->data);
        $order->user_id = $this->user->getID();

        $order = $this->orders->updateOrder($order);

        return new ApiResponse([
            "data"=> $order->toArray()
        ]);
    }

    protected function _delete() {
        $order = new Order([
            'id' => $this->order_id,
            'user_id' => $this->user->getID()
        ]);

        $this->orders->removeOrder($order);

        return new ApiResponse();
    }
}

?>