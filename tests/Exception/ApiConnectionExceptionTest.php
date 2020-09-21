<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class ApiConnectionExceptionTest extends TestCase {

  public $apiConnectionException;

  public function setUp() {
    $this->apiConnectionException = new ApiConnectionException(
      "Magic is amazing"
    );
  }

  public function testGetRepr() {
    $this->assertEquals("ApiConnectionException(message=Magic is amazing", $this->apiConnectionException->getRepr());
  } 
}
