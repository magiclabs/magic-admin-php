<?php

use MagicAdmin\Util;
use PHPUnit\Framework\TestCase;

class UtilTimeTest extends TestCase {

  public function test_epoch_time_now() {
    $this->assertEquals(time(), \MagicAdmin\Util\UtilTime::epoch_time_now());
  }

  public function test_apply_did_token_nbf_grace_period() {
    $timestamp = 8084;
    $this->assertEquals( $timestamp - DID_TOKEN_NBF_GRACE_PERIOD_S, \MagicAdmin\Util\UtilTime::apply_did_token_nbf_grace_period($timestamp) );
  } 
}
