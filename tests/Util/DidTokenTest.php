<?php

use MagicAdmin\Util; 
use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class UtilDidTokenTest extends TestCase {

  public function test_parse_public_address_from_issuer() {

    $public_address = '0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    $issuer = 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2';

    $this->assertEquals($public_address, \MagicAdmin\Util\UtilDidToken::parse_public_address_from_issuer($issuer));
  }

  public function test_construct_issuer_with_public_address() {
    
    $public_address = '0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    $issuer = 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2';

    $this->assertEquals('did:ethr:' . $public_address, \MagicAdmin\Util\UtilDidToken::construct_issuer_with_public_address($public_address));
  }
}
