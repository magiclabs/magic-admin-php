<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class DIDTokenExceptionTest extends TestCase {

  public $dIDTokenException;

  public function setUp() {
    $this->dIDTokenException = new MagicAdmin\Exception\DIDTokenException("Magic is amazing");
  }

  public function testGetRepr() {
    $this->assertEquals("MagicAdmin\Exception\DIDTokenException(message=Magic is amazing)", $this->dIDTokenException->getRepr());
  } 
}
