<?php
namespace Vlada;

abstract class ApiModule {
    /**
     * @var Api
     */
    protected $request;
    /**
     * @var MySQL
     */
    protected $database;
    
    /**
     * @param Api $request
     * @param Database $database
     */
    public function __construct(Api $request, $database) {
        $this->request = $request;
        $this->database = $database;
    }

    public function run() {
        $this->_run();
        return $this->runMethod();
    }

    private function runMethod() {
        if ($this->request->method == "OPTIONS")
            return;

        $methodName = $this->getMethodFunc();
        // if ( method_exists($this, $methodName) )
        return $this->$methodName();
    }

    private function getMethodFunc() {
        return '_'.strtolower($this->request->method);
    }

    private function methodNotAllowed() {
        throw new \Exception("Method not allowed", ApiErrors::INPUT_ERROR);
    }

    /**
     * @return ApiResponse
     */
    abstract protected function _run();

    protected function _get() {
        $this->methodNotAllowed();
    }

    protected function _post() {
        $this->methodNotAllowed();
    }

    protected function _put() {
        $this->methodNotAllowed();
    }

    protected function _delete() {
        $this->methodNotAllowed();
    }
}

?>