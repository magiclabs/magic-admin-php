<?php

namespace MagicAdmin\Exception; 

/**
 * DIDTokenException is thrown in the event that DID token is missing, 
 * DID token is malformed, given DID token has expired or 
 * signature mismatch between "proof" and "claim".
 */

class DIDTokenException extends \Exception {
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
