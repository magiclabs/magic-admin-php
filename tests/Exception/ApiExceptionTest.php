<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class ApiExceptionTest extends TestCase {

  public $apiException;

  public function setUp() {
    $this->apiException = new ApiException(
      "Magic is amazing"
    );
  }

  public function testGetRepr() {
    $this->assertEquals("ApiException(message=Magic is amazing", $this->apiException->getRepr());
  } 
}
