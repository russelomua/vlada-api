<?php

namespace Vlada;

class Api {
    /**
     * @var String
     */
    public $method;

    /**
     * @var Object
     */
    public $data;

    /**
     * PHP Raw input
     * @var string
     */
    public $input;

    /**
     * @var Array
     */
    public $action_list;

    /**
     * @var String
     */
    public $module;

    /**
     * @var Array
     */
    public $query = [];

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        //Если пришел preflight, отдаем заголовки и завершаем выполнение кода
		if ($this->method == "OPTIONS")
            $this->option_headers();

        $this->action_list = $this->getAction();
    
        $this->module = $this->action_list[0];
        array_splice($this->action_list, 0, 1);

        $this->data = $this->load();
        $this->input = $this->input();
        $this->getQuery();
    }

    private function load() {
		$data = $this->input();
		$data = json_decode($data);
		return $data;
    }

    private function input() {
		return file_get_contents('php://input');
    }

    private function getQuery() {
        $queryString = preg_replace('/^.*\?/', '', $_SERVER['REQUEST_URI']);
        if (!empty($queryString))
            parse_str($queryString, $this->query);

        foreach ($this->query as $key => $value) {
            if (is_numeric($value))
                $this->query[$key] = $value+0;
        }
    }

    private function getAction() {
        $actionString = preg_replace('/\&.*/', '', $_SERVER['QUERY_STRING']);
        return explode('/', $actionString);
    }
    
    /**
     * @param ApiResponse
     */
    public function send(ApiResponse $response) {
        $this->headers();
        if (!($response instanceof ApiResponse))
            http_response_code(500);
        
		http_response_code($response->status);
		
		if (!is_null($response->data))
			echo json_encode($response->data);
    }
    
    private function headers() {
		header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');
		header('Access-Control-Allow-Credentials: false');
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, X-Access-Token');		
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: 0");
		header('Content-Type: application/json;charset=utf-8');
    }
    
    function option_headers() {
		$this->headers();
		header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: application/json;charset=utf-8');
		http_response_code(200);
        die();
    }

    function getToken() {
        if (array_key_exists('HTTP_X_ACCESS_TOKEN', $_SERVER))
            return $_SERVER['HTTP_X_ACCESS_TOKEN'];
        return false;
    }
}

?>