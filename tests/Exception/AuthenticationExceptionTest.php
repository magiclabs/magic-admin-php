<?php

use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class AuthenticationExceptionTest extends TestCase {

  public $authenticationException;

  public function setUp() {
    $this->authenticationException = new AuthenticationException(
      "Magic is amazing"
    );
  }

  public function testGetRepr() {
    $this->assertEquals("AuthenticationException(message=Magic is amazing", $this->authenticationException->getRepr());
  } 
}
