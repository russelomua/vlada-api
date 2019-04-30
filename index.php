<?php
use Vlada\Api;
use Vlada\ApiResponse;
use Vlada\ApiErrors;
use Vlada\MySQL;

include_once("./config.php");
include_once(ROOT_DIR."/autoload.php");

// connection
$database = new MySQL(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);

$request = new Api();

// if (in_array($request->module, ["uplink","join","status","location","ack","error"]))
//      "Vlada\Modules\Input";
//  else 
$classname = "Vlada\Modules\\".ucfirst($request->module);

try {
    if (!class_exists($classname)) 
        throw new Exception("Missing node", ApiErrors::MODULE_ERROR);

    $response = (new $classname($request, $database))->run();

    if ($response instanceof ApiResponse)
        $request->send($response);
    else
        $request->send(new ApiResponse(["data" => $response, "status" => 200]));
} catch (Exception $e) {
    if ($e->getCode() == ApiErrors::SERVER_ERROR)
        $request->send(new ApiResponse(["status" => 300]));
    else 
        $request->send(new ApiResponse(["data"=> ["error" => $e->getMessage()], "status" => $e->getCode()]));
}


?>