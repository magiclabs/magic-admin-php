<?php

namespace MagicAdmin\Exception; 

/**
 * ApiConnectionException is thrown in the event that 
 * the SDK can't connect to Magic's servers.
 */

class ApiConnectionException extends \Exception {
  private $_message = "";

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
