<?php
use Vlada\Api;
use Vlada\ApiResponse;
use Vlada\ApiErrors;
use Vlada\MySQL;
use Vlada\Models\User;
use Vlada\Database\Users;
use Firebase\JWT\JWT;

include_once("./config.php");
include_once(ROOT_DIR."/vendor/autoload.php");
include_once(ROOT_DIR."/autoload.php");

// connection
$database = new MySQL(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);

$request = new Api();

// if (in_array($request->module, ["uplink","join","status","location","ack","error"]))
//      "Vlada\Modules\Input";
//  else 
$classname = "Vlada\Modules\\".ucfirst($request->module);

try {
    /**
     * Start session
     */
    try {
        $payload = (array) JWT::decode($request->getToken(), API_SECRET, array('HS256'));
        
        $user = (new Users())->getByID($payload['user_id']);
    } catch (Exception $e) {}
        
    if (!class_exists($classname)) 
        throw new Exception("Missing node", ApiErrors::MODULE_ERROR);

    $response = (new $classname($request, $database))->run();

    if ($response instanceof ApiResponse)
        $request->send($response);
    else
        $request->send(new ApiResponse(["data" => $response, "status" => 200]));
} catch (Exception $e) {
    if ($e->getCode() == ApiErrors::SERVER_ERROR)
        $request->send(new ApiResponse(["status" => ApiErrors::SERVER_ERROR]));
    else 
        $request->send(new ApiResponse(["data"=> ["message" => $e->getMessage()], "status" => $e->getCode()]));
}


?>