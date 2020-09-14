<?php

namespace MagicAdmin; 

use MagicAdmin\Exception;
use MagicAdmin\Resource;

define("RETRIES", 3);
define("TIMEOUT", 10);
define("BACKOFF_FACTOR", 0.02);

class Magic {

    public $token;
    public $_resource;
    public $api_secret_key = null;
    public $user;

    public function __construct(
        $api_secret_key = null,
        $retries = RETRIES,
        $timeout = TIMEOUT,
        $backoff_factor = BACKOFF_FACTOR
    ) {        
        $this->_set_api_secret_key($api_secret_key);
        $this->token = new \MagicAdmin\Resource\Token();
        $this->user = new \MagicAdmin\Resource\User($this->api_secret_key, $retries, $timeout, $backoff_factor);
    }

    public function _set_api_secret_key($api_secret_key) {
        $this->api_secret_key = $api_secret_key;
    }
}