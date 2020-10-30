<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class RateLimitingExceptionTest extends TestCase {

  public $rateLimitException;

  public function setUp() {
    $this->rateLimitException = new RateLimitingException("Magic is amazing");
  }

  public function testGetRepr() {
    $this->assertEquals("RateLimitingException(message=Magic is amazing", $this->rateLimitException->getRepr());
  } 
}
