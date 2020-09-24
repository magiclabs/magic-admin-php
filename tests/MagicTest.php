<?php

use MagicAdmin;
use PHPUnit\Framework\TestCase;

class MagicTest extends TestCase {

  public $magic;
  public $api_secret_key = "magic_admin";
  public $timeout = 10;
  public $retries = 3;
  public $backoff_factor = 0.02;

  public function setUp() { 
    $this->magic = new \MagicAdmin\Magic($this->$api_secret_key, $this->$timeout, $this->$retries, $this->$backoff_factor);
  }

  public function test_retrieves_api_secret_key() { 
    assert($this->magic->api_secret_key, $this->api_secret_key);
  }

  public function test_retrieves_timeout() { 
    assert($this->magic->timeout, $this->timeout);
  }

  public function test_retrieves_retries() { 
    assert($this->magic->retries, $this->retries);
  }

  public function test_retrieves_backoff_factor() { 
    assert($this->magic->backoff_factor, $this->backoff_factor);
  } 
}
