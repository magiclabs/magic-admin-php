<?php

namespace MagicAdmin\Resource; 

use MagicAdmin\Exception;
use MagicAdmin\Util;
use Ethereum\EcRecover;

define("EXPECTED_DID_TOKEN_CONTENT_LENGTH", 2);

class Token {
  public $required_fields = array(
    'iat',
    'ext',
    'nbf',
    'iss',
    'sub',
    'aud',
    'tid',
  );

  public function _check_required_fields($claim) {
    $missing_fields = array();
    foreach ($this->required_fields as $field) {
      if (!array_key_exists($field, $claim)) {
        array_push($missing_fields, $field);
      }
    }

    if (count($missing_fields) > 0) {
      throw new \MagicAdmin\Exception\DIDTokenException( 
        'DID token is missing required field(s):' . json_encode($missing_fields)
      );
    } else {
      return null;
    }
  }

  public function decode($did_token) {
    try {
      $decoded_did_token = json_decode( utf8_decode( base64_decode( $did_token ) ) );            
    } catch (Exception $e) {
      throw new \MagicAdmin\Exception\DIDTokenException( 
        'DID token is malformed. It has to be a based64 encoded JSON serialized string. DIDTokenException(' .  $e->getMessage() . ')' 
      );
    }

    if (count($decoded_did_token) != EXPECTED_DID_TOKEN_CONTENT_LENGTH) {
      throw new \MagicAdmin\Exception\DIDTokenException( 
        'DID token is malformed. It has to have two parts [proof, claim].' 
      );
    }

    $proof = $decoded_did_token[0];

    try {
      $claim = json_decode($decoded_did_token[1]);
    } catch (Exception $e) {
      throw new \MagicAdmin\Exception\DIDTokenException( 
        'DID token is malformed. Given claim should be a JSON serialized string. DIDTokenException(' .  $e->getMessage() . ')' 
      );
    }

    $this->_check_required_fields($claim);

    return array($proof, $claim);
  }

  public function get_issuer($did_token) {
    list($proof , $claim) = $this->decode($did_token);
    return $claim->iss;
  }

  public function get_public_address($did_token) {
    return \MagicAdmin\Util\UtilDidToken::parse_public_address_from_issuer($this->get_issuer($did_token));
  }

  public function validate($did_token) {
    list($proof, $claim) = $this->decode($did_token);

    $recovered_address = EcRecover::personalEcRecover(json_encode($claim), $proof);

    if ($recovered_address != $this->get_public_address($did_token)) {
      throw new \MagicAdmin\Exception\DIDTokenException(
        'Signature mismatch between "proof" and "claim". Please generate a new token with an intended issuer.'
      ); 
    }

    $current_time_in_s = \MagicAdmin\Util\UtilTime::epoch_time_now();

    if ($current_time_in_s > $claim->ext) {
      throw new \MagicAdmin\Exception\DIDTokenException(
        'Given DID token has expired. Please generate a new one.'
      ); 
    }

    if ($current_time_in_s < \MagicAdmin\Util\UtilTime::apply_did_token_nbf_grace_period($claim->nbf)) { 
      throw new \MagicAdmin\Exception\DIDTokenException(
        'Given DID token cannot be used at this time. Please check the "nbf" field and regenerate a new token with a suitable value.'
      ); 
    }
  }
} 
