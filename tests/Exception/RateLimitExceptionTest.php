<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class RateLimitingExceptionTest extends TestCase {

  public $rateLimitException;

  public function setUp() {
    $this->rateLimitException = new MagicAdmin\Exception\RateLimitException(
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
    $this->assertEquals("MagicAdmin\Exception\RateLimitException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=200)", $this->rateLimitException->getRepr());
  } 
}
