<?php

namespace MagicAdmin\Exception; 

/**
 * Magic custom exception 
 */

class MagicException extends \Exception  {

  public $_message = "";

  public function __construct($message=null) {
    parent::__construct($message);
    $this->_message = $message;
  }

  public function getErrorMessage() {
    return $this->_message;
  }

  public function getRepr() {
    return get_class($this) . '(message=' . $this->_message . ')';
  }
}
