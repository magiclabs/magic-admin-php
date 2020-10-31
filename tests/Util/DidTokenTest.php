<?php

use MagicAdmin\Util; 
use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class UtilDidTokenTest extends TestCase {

  private $public_address;
  private $issuer;

  public function setUp() {
    $this->public_address = '0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    $this->issuer = 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
  }

  public function test_parse_public_address_from_issuer() { 
    $this->assertEquals(strtolower($this->public_address), \MagicAdmin\Util\UtilDidToken::parse_public_address_from_issuer($this->issuer));
  }

  public function test_construct_issuer_with_public_address() { 
    $this->assertEquals('did:ethr:' . $this->public_address, \MagicAdmin\Util\UtilDidToken::construct_issuer_with_public_address($this->public_address));
  }
}
