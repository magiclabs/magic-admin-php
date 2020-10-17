<?php

use MagicAdmin;
use PHPUnit\Framework\TestCase;

class MagicResponseTest() {

  public $magicResponse;
  public $content = "magic link";
  public $resp_data = "magic is amazing";
  public $status_code = 200;

  public function setUp() {    
    $this->magicResponse = new \MagicAdmin\MagicResponse($this->$content, $this->$resp_data, $this->$status_code);
  }

  public function test_retrieves_content() { 
    $this->assertEquals($this->magicResponse->content, $this->content);
  }

  public function test_retrieves_resp_data() { 
    $this->assertEquals($this->magicResponse->resp_data, $this->resp_data);
  }

  public function test_retrieves_status_code() { 
    $this->assertEquals($this->magicResponse->status_code, $this->status_code);
  }
}
