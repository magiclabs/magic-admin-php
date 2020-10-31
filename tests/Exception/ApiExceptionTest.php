<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class ApiExceptionTest extends TestCase {

  public $apiException;

  public function setUp() {
    $this->apiException = new MagicAdmin\Exception\ApiException(
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
    $this->assertEquals("MagicAdmin\Exception\ApiException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=200)", $this->apiException->getRepr());
  } 
}
