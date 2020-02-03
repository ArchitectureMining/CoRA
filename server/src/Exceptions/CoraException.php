<?php

namespace Cora\Exceptions;

class CoraException extends \Exception {

    protected $http;

    public function __construct($message, $http, $code = 0, \Exception $prev = null) {
        parent::__construct($message, $code, $prev);
        $this->http = $http;
    }

    public function getHttpStatus() {
        return $this->http;
    }
}

?>