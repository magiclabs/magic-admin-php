<?php

use MagicAdmin\Resource
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {

  public $user;

  public function setUp() {
    $api_secret_key = "magic_admin";
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 1;
    $this->user = new \MagicAdmin\Resource\User($api_secret_key, $timeout, $retries, $backoff_factor);
  }

  public function test_get_metadata_by_issuer() {
    $issuer = 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    $public_address = '0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    $meta_data = $this->user->get_metadata_by_issuer($issuer);
    $this->assertEquals($meta_data['data']->issuer, $issuer);
    $this->assertEquals($meta_data['data']->public_address, $public_address);
  }

  public function test_logout_by_issuer() {
    $issuer = 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    $public_address = '0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    $meta_data = $this->user->logout_by_issuer($issuer);
    $this->assertEquals($meta_data['data']->issuer, $issuer);
    $this->assertEquals($meta_data['data']->public_address, $public_address);
  }

}
