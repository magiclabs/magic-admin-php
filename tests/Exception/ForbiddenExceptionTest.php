<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class ForbiddenExceptionTest extends TestCase {

  public $forbiddenException;

  public function setUp() {
    $this->forbiddenException = new ForbiddenException(
      "Magic is amazing"
    );
  }

  public function testGetRepr() {
    $this->assertEquals("ForbiddenException(message=Magic is amazing", $this->forbiddenException->getRepr());
  } 
}
