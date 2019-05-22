<?php
namespace Vlada\Modules;

use Vlada\ApiModule;
use Vlada\ApiResponse;
use Vlada\Models\DronQueue;

class Office extends ApiModule {
    protected function _run() {
    }

    protected function _get() {
        return new ApiResponse([
            "data"=> DronQueue::OFFICE
        ]);
    }
}

?>