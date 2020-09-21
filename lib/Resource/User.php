<?php

namespace MagicAdmin\Resource; 

use MagicAdmin\Util;

class User {

  public $v1_user_info = '/v1/admin/auth/user/get';
  public $v2_user_logout = '/v2/admin/auth/user/logout';

  public $request_client;
  public $token;

  public function __construct($api_secret_key, $timeout, $retries, $backoff_factor) {
    $this->token = new Token();
    $this->request_client = new \MagicAdmin\RequestsClient($api_secret_key, $timeout, $retries, $backoff_factor);
  }

  public function get_metadata_by_issuer($issuer) {
    return $this->request_client->request('get', $this->v1_user_info, array('issuer' => $issuer));
  }

  public function get_metadata_by_public_address($public_address) {
    return $this->get_metadata_by_issuer(
      \MagicAdmin\Util\UtilDidToken::construct_issuer_with_public_address($public_address)
    );
  }

  public function get_metadata_by_token($did_token) {
    return $this->get_metadata_by_issuer($this->token->get_issuer($did_token));
  }

  public function logout_by_issuer($issuer) {
    return $this->request_client->request('post', $this->v2_user_logout, null, array('issuer'=> $issuer));
  }

  public function logout_by_public_address($public_address) {
    return $this->logout_by_issuer(
      \MagicAdmin\Util\UtilDidToken::construct_issuer_with_public_address($public_address)
    );
  }

  public function logout_by_token($did_token) {
    return $this->logout_by_issuer($this->token->get_issuer($did_token));
  }    
}
