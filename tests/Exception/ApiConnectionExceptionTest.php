<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class ApiConnectionExceptionTest extends TestCase {

  public $apiConnectionException;

  public function setUp() {
    $this->apiConnectionException = new MagicAdmin\Exception\ApiConnectionException("Magic is amazing");
  }

  public function testGetRepr() {
    $this->assertEquals("MagicAdmin\Exception\ApiConnectionException(message=Magic is amazing)", $this->apiConnectionException->getRepr());
  } 
}
