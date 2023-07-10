<?php

namespace MagicAdmin\Resource;

class User
{
    public $v1_user_info = '/v1/admin/auth/user/get';
    public $v2_user_logout = '/v2/admin/auth/user/logout';

    public $request_client;
    public $token;

    public function __construct($request_client, $token)
    {
      $this->request_client = $request_client;
      $this->token = $token;
    }

    public function get_metadata_by_issuer_and_wallet($issuer, $wallet_type)
    {
        return $this->request_client->request('get', $this->v1_user_info, ['issuer' => $issuer, 'wallet_type' => $wallet_type]);
    }

    public function get_metadata_by_issuer($issuer)
    {
        return $this->get_metadata_by_issuer_and_wallet($issuer, Wallet::NONE);
    }

    public function get_metadata_by_public_address_and_wallet($public_address, $wallet_type)
    {
        return $this->get_metadata_by_issuer(
            \MagicAdmin\Util\DidToken::construct_issuer_with_public_address($public_address),
            $wallet_type
        );
    }

    public function get_metadata_by_public_address($public_address)
    {
        return $this->get_metadata_by_public_address_and_wallet($public_address, Wallet::NONE);
    }

    public function get_metadata_by_token_and_wallet($did_token, $wallet_type)
    {
        return $this->get_metadata_by_issuer($this->token->get_issuer($did_token), $wallet_type);
    }

    public function get_metadata_by_token($did_token)
    {
        return $this->get_metadata_by_token_and_wallet($did_token, Wallet::NONE);
    }

    public function logout_by_issuer($issuer)
    {
        return $this->request_client->request('post', $this->v2_user_logout, null, ['issuer' => $issuer]);
    }

    public function logout_by_public_address($public_address)
    {
        return $this->logout_by_issuer(
            \MagicAdmin\Util\DidToken::construct_issuer_with_public_address($public_address)
        );
    }

    public function logout_by_token($did_token)
    {
        return $this->logout_by_issuer($this->token->get_issuer($did_token));
    }

    public function _set_platform($platform)
    {
        $this->request_client->_set_platform($platform);
    }
}
