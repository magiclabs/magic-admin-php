<?php

namespace MagicAdmin\Exception; 

/**
 * ForbiddenException is thrown in the event that 
 * the SDK request is not allowed to access to server.
 */

class ForbiddenException extends \Exception {
  private $_message;
  private $http_status;
  private $http_code;
  private $http_resp_data;
  private $http_message;
  private $http_error_code;
  private $http_request_params;
  private $http_request_data;
  private $http_method;

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
    $this->_message = $message;
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
