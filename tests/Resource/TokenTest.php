<?php

use MagicAdmin\Resource
use MigicAdmin\Exception
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase {

  public $token;

  public function setUp() {
    $this->token = new \MagicAdmin\Resource\Token();
  }

  public function test_check_required_fields() {
    $claim = array(
      'iat' => 1586764270,
      'ext' => 11173528500,
      'iss' => 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2',
      'sub' => 'NjrA53ScQ8IV80NJnx4t3Shi9-kFfF5qavD2Vr0d1dc=',
      'aud' => 'did:magic:731848cc-084e-41ff-bbdf-7f103817ea6b',
      'nbf' => 1586764270,
      'tid' => 'ebcc880a-ffc9-4375-84ae-154ccd5c746d',
      'add' => '0x84d6839268a1af9111fdeccd396f303805dca2bc03450b7eb116e2f5fc8c5a722d1fb9af233aa73c5c170839ce5ad8141b9b4643380982da4bfbb0b11284988f1b'
    );
    $this->assertEquals($this->token->_check_required_fields($claim), null);
  }

  public function test_check_required_fields_missing() {
    $claim = array(
      'iat' => 1586764270,
      'ext' => 11173528500,
      'iss' => 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2',
      'sub' => 'NjrA53ScQ8IV80NJnx4t3Shi9-kFfF5qavD2Vr0d1dc=',
      'aud' => 'did:magic:731848cc-084e-41ff-bbdf-7f103817ea6b'
    );

    try {
      $this->token->_check_required_fields($claim);
    } catch (\MagicAdmin\Exception\DIDTokenException $e) {
      $this->assertEquals($e->message, 'DID token is missing required field(s):[\'nbf\', \'tid\']');
    }
  }
}


class TokenDecodeTest extends TestCase {

  public $token;

  public function setUp() {
    $this->token = new \MagicAdmin\Resource\Token();
  }

  public function test_decode_raises_error_if_did_token_is_malformed() {
    $did_token = "magic_token";
    try {
      $this->token->decode($did_token);
    } catch ( \MagicAdmin\Exception\DIDTokenException $e ) {
      $this->assertEquals($e->message, 'DID token is malformed. It has to be a based64 encoded JSON serialized string. DIDTokenException(<empty message>).');
    }
  }

  public function test_decode_raises_error_if_did_token_is_missing_parts() {
    $did_token = "magic_token";
    try {
      $this->token->decode($did_token);
    } catch ( \MagicAdmin\Exception\DIDTokenException $e ) {
      $this->assertEquals($e->message, 'DID token is malformed. It has to have two parts [proof, claim].');
    }
  }

  public function test_decode_raises_error_if_claim_is_not_json_serializable() {
    $did_token = "magic_token";
    try {
      $this->token->decode($did_token);
    } catch ( \MagicAdmin\Exception\DIDTokenException $e ) {
      $this->assertEquals($e->message, 'DID token is malformed. Given claim should be ' \
            'a JSON serialized string. DIDTokenException(<empty message>).');
    }
  }
}
