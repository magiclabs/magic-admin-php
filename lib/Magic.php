<?php

namespace MagicAdmin;

\define('TIMEOUT', 10);
\define('RETRIES', 3);
\define('BACKOFF_FACTOR', 0.02);

class Magic
{
    public $token;
    public $api_secret_key;
    public $user;
    public $wallet_type;

    public function __construct(
        $api_secret_key = null,
        $timeout = TIMEOUT,
        $retries = RETRIES,
        $backoff_factor = BACKOFF_FACTOR
    ) {
        $this->api_secret_key = $api_secret_key;
        $this->wallet_type = new \MagicAdmin\Resource\WalletType();
        $this->token = new \MagicAdmin\Resource\Token();
        $this->user = new \MagicAdmin\Resource\User(
            $this->api_secret_key,
            $timeout,
            $retries,
            $backoff_factor
        );
    }
}
