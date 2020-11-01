<?php

use MagicAdmin\Util;
use PHPUnit\Framework\TestCase;

class UtilHttpTest extends TestCase {

  public function test_parse_authorization_header_value() {
    $expected = 'Bearer magic_admin';
    $malformed = 'wrong_format';
    $this->assertEquals("magic_admin", \MagicAdmin\Util\UtilHttp::parse_authorization_header_value($expected));

    $this->assertEquals(null, \MagicAdmin\Util\UtilHttp::parse_authorization_header_value($malformed));
  } 
}
