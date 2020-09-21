<?php

use MagicAdmin;
use PHPUnit\Framework\TestCase;

class MagicTest extends TestCase {

  public $magic;

  public function setUp() {
    $api_secret_key = "magic_admin";
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 1;
    $this->magic = new \MagicAdmin\Magic($api_secret_key, $timeout, $retries, $backoff_factor);
  }

  public function test_retrieves_api_secret_key() {
    $expected_api_secret_key = "magic_admin";
    assert($this->magic->api_secret_key, $expected_api_secret_key);
  }

  public function test_retrieves_timeout() {
    $expected_timeout = 10;
    assert($this->magic->timeout, $expected_timeout);
  }

  public function test_retrieves_retries() {
    $expected_retries = 3;
    assert($this->magic->retries, $expected_retries);
  }

  public function test_retrieves_backoff_factor() {
    $expected_backoff_factor = 1;
    assert($this->magic->backoff_factor, $expected_backoff_factor);
  }

}
