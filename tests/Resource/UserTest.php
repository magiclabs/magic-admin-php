<?php

use MagicAdmin\Resource;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {

  public $user;
  private $issuer;
  private $public_address;

  public function setUp() {
    $api_secret_key = "sk_test_6F832D5FB8382105";
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 0.02;
    $this->user = new \MagicAdmin\Resource\User($api_secret_key, $timeout, $retries, $backoff_factor);
    $this->issuer = 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4';
    $this->public_address = '0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4';
  }

  public function test_get_metadata_by_issuer() { 
    $meta_data = $this->user->get_metadata_by_issuer($this->issuer);
    $this->assertEquals($meta_data->data->issuer, $this->issuer);
    $this->assertEquals($meta_data->data->public_address, $this->public_address);
  } 
}
