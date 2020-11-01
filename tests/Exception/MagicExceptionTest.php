<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class MagicExceptionTest extends TestCase  {

  public $magicException;

  public function setUp() {
    $this->magicException = new MagicAdmin\Exception\MagicException("Magic is amazing");
  }

  public function testGetErrorMessage() {
    $this->assertEquals("Magic is amazing", $this->magicException->getErrorMessage());
  }

  public function testGetRepr() {
    $this->assertEquals("MagicAdmin\Exception\MagicException(message=Magic is amazing)", $this->magicException->getRepr());
  }
}
