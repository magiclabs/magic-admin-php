<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class RateLimitExceptionTest extends TestCase {

  public $rateLimitException;

  public function setUp() {
    $this->rateLimitException = new RateLimitException(
      "Magic is amazing"
    );
  }

  public function testGetRepr() {
    $this->assertEquals("RateLimitException(message=Magic is amazing", $this->rateLimitException->getRepr());
  } 
}
