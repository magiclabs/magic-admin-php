<?php

use MagicAdmin\Util;
use PHPUnit\Framework\TestCase;

class UtilHttpTest extends TestCase {

  public function test_parse_authorization_header_value() {
    $expected = 'Bearer magic_admin';
    $this->assertEquals($expected, \MagicAdmin\Util\UtilHttp::parse_authorization_header_value($expected));
  }

  public function test_parse_authorization_header_value() {
    $malformed = 'wrong_format';
    $this->assertEquals(null, \MagicAdmin\Util\UtilHttp::parse_authorization_header_value($malformed));
  } 
}
