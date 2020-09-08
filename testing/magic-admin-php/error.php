<?php

namespace MagicAdmin;

class MagicError extends \Exception  {

    public $_message = "";

    public function __construct($message=null) {
        parent::__construct($message);
        $this->_message = $message;
    }

    public function getErrorMessage() {
        return $this->_message;
    }

    public function getRepr() {
        return __class__ . '(message=' . $this->_message . ')';
    }
}


class DIDTokenError extends MagicError {}

class APIConnectionError extends MagicError {}

class RequestError extends MagicError {

    public $http_status;
    public $http_code;
    public $http_resp_data;
    public $http_message;
    public $http_error_code;
    public $http_request_params;
    public $http_request_data;
    public $http_method;

    public function __construct(
        $message=null,
        $http_status=null,
        $http_code=null,
        $http_resp_data=null,
        $http_message=null,
        $http_error_code=null,
        $http_request_params=null,
        $http_request_data=null,
        $http_method=null
    ) {
        parent::__construct($message);
        $this->http_status = $http_status;
        $this->http_code = $http_code;
        $this->http_resp_data = $http_resp_data;
        $this->http_message = $http_message;
        $this->http_error_code = $http_error_code;
        $this->http_request_params = $http_request_params;
        $this->http_request_data = $http_request_data;
        $this->http_method = $http_method;
    }

    public function getRepr() {

        return __class__ . 
            '(message=' . $this->_message .
            ', http_error_code=' . $this->http_error_code . 
            ', http_code=' . $this->http_code . ')';
    }

}

class RateLimitingError extends RequestError {}

class BadRequestError extends RequestError {}

class AuthenticationError extends RequestError {}

class ForbiddenError extends RequestError {}

class APIError extends RequestError {}


    