<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class BadRequestExceptionTest extends TestCase {

  public $badRequestException;

  public function setUp() {
    $this->badRequestException = new MagicAdmin\Exception\BadRequestException(
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
    $this->assertEquals("MagicAdmin\Exception\BadRequestException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=200)", $this->badRequestException->getRepr());
  } 
}
