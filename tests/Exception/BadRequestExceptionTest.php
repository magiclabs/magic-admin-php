<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class BadRequestExceptionTest extends TestCase {

  public $badRequestException;

  public function setUp() {
    $this->badRequestException = new BadRequestException(
      "Magic is amazing"
    );
  }

  public function testGetRepr() {
    $this->assertEquals("BadRequestException(message=Magic is amazing", $this->badRequestException->getRepr());
  } 
}
