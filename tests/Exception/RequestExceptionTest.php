<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class RequestExceptionTest extends TestCase {

  public $requestException;

  public function setUp() {
    $this->requestException = new RequestException(
      "Magic is amazing",
      "Magic is good",
      "success",
      200,
      array("magic" => "link"),
      "Magic is good",
      "MAGIC_IS_GOOD",
      "a=b&b=c",
      array("magic" => "link"),
      "post"
    );
  }

  public function testGetRepr() {
    $this->assertEquals("RequestException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=200", $this->getRepr());
  } 
}
