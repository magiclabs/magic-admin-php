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
    $mock = $this->createMock(\MagicAdmin\Resource\User::class);
    $mock->method('get_metadata_by_issuer')
         ->with($this->issuer)
         ->willReturn(
            new \MagicAdmin\MagicResponse(
              (object) array(
                "data" => (object) array(
                  "email" => "test@user.com",
                  "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                  "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
                ),
                "error_code" => "",
                "message" => "",
                "status" => "ok"
              ),
              (object) array(
                "email" => "test@user.com",
                "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
              ),
              200
            )
          );

    $meta_data = $mock->get_metadata_by_issuer($this->issuer);

    $this->assertEquals($meta_data->data->issuer, $this->issuer);
    $this->assertEquals($meta_data->data->public_address, $this->public_address);
  } 

  public function test_get_metadata_by_public_address() { 
    $mock = $this->createMock(\MagicAdmin\Resource\User::class);
    $mock->method('get_metadata_by_public_address')
         ->with($this->public_address)
         ->willReturn(
            new \MagicAdmin\MagicResponse(
              (object) array(
                "data" => (object) array(
                  "email" => "test@user.com",
                  "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                  "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
                ),
                "error_code" => "",
                "message" => "",
                "status" => "ok"
              ),
              (object) array(
                "email" => "test@user.com",
                "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
              ),
              200
            )
          );

    $meta_data = $mock->get_metadata_by_public_address($this->public_address);

    $this->assertEquals($meta_data->data->issuer, $this->issuer);
    $this->assertEquals($meta_data->data->public_address, $this->public_address);
  }

  public function test_get_metadata_by_token() { 
    $did_token = "magic_token";
    $mock = $this->createMock(\MagicAdmin\Resource\User::class);
    $mock->method('get_metadata_by_token')
         ->with($did_token)
         ->willReturn(
            new \MagicAdmin\MagicResponse(
              (object) array(
                "data" => (object) array(
                  "email" => "test@user.com",
                  "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                  "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
                ),
                "error_code" => "",
                "message" => "",
                "status" => "ok"
              ),
              (object) array(
                "email" => "test@user.com",
                "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
              ),
              200
            )
          );

    $meta_data = $mock->get_metadata_by_token($did_token);

    $this->assertEquals($meta_data->data->issuer, $this->issuer);
    $this->assertEquals($meta_data->data->public_address, $this->public_address);
  }

  public function test_logout_by_issuer() { 
    $mock = $this->createMock(\MagicAdmin\Resource\User::class);
    $mock->method('logout_by_issuer')
         ->with($this->issuer)
         ->willReturn(
            new \MagicAdmin\MagicResponse(
              (object) array(
                "data" => (object) array(
                  "email" => "test@user.com",
                  "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                  "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
                ),
                "error_code" => "",
                "message" => "",
                "status" => "ok"
              ),
              (object) array(
                "email" => "test@user.com",
                "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
              ),
              200
            )
          );

    $meta_data = $mock->logout_by_issuer($this->issuer);

    $this->assertEquals($meta_data->data->issuer, $this->issuer);
    $this->assertEquals($meta_data->data->public_address, $this->public_address);
  }

  public function test_logout_by_public_address() { 
    $mock = $this->createMock(\MagicAdmin\Resource\User::class);
    $mock->method('logout_by_public_address')
         ->with($this->public_address)
         ->willReturn(
            new \MagicAdmin\MagicResponse(
              (object) array(
                "data" => (object) array(
                  "email" => "test@user.com",
                  "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                  "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
                ),
                "error_code" => "",
                "message" => "",
                "status" => "ok"
              ),
              (object) array(
                "email" => "test@user.com",
                "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
              ),
              200
            )
          );

    $meta_data = $mock->logout_by_public_address($this->public_address);

    $this->assertEquals($meta_data->data->issuer, $this->issuer);
    $this->assertEquals($meta_data->data->public_address, $this->public_address);
  }

  public function test_logout_by_token() { 
    $did_token = "magic_token";
    $mock = $this->createMock(\MagicAdmin\Resource\User::class);
    $mock->method('logout_by_token')
         ->with($did_token)
         ->willReturn(
            new \MagicAdmin\MagicResponse(
              (object) array(
                "data" => (object) array(
                  "email" => "test@user.com",
                  "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                  "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
                ),
                "error_code" => "",
                "message" => "",
                "status" => "ok"
              ),
              (object) array(
                "email" => "test@user.com",
                "issuer" => "did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4",
                "public_address" => "0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"
              ),
              200
            )
          );

    $meta_data = $mock->logout_by_token($did_token);

    $this->assertEquals($meta_data->data->issuer, $this->issuer);
    $this->assertEquals($meta_data->data->public_address, $this->public_address);
  }
}
