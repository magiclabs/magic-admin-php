<?php

use MagicAdmin;
use PHPUnit\Framework\TestCase;

class MagicResponseTest() {

  public $magicResponse;

  public function setUp() {
    $content = "magic link";
    $resp_data = "magic is amazing";
    $status_code = 200;
    $this->magicResponse = new \MagicAdmin\MagicResponse($content, $resp_data, $status_code);
  }

  public function test_retrieves_content() {
    $expected_content = "magic link";
    $this->assertEquals($this->magicResponse->content, $expected_content);
  }

  public function test_retrieves_resp_data() {
    $expected_resp_data = "magic is amazing";
    $this->assertEquals($this->magicResponse->resp_data, $expected_resp_data);
  }

  public function test_retrieves_status_code() {
    $expected_status_code = 200;
    $this->assertEquals($this->magicResponse->status_code, $expected_status_code);
  }
}
