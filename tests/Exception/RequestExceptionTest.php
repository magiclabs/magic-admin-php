<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class RequestExceptionTest extends TestCase {

  public $requestException;

  public function setUp() {
    $this->requestException = new MagicAdmin\Exception\RequestException(
      "Magic is amazing",
      "Magic is good", 
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
    $this->assertEquals("MagicAdmin\Exception\RequestException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=200)", $this->requestException->getRepr());
  } 
}
