<?php

namespace MagicAdmin; 

use MagicAdmin\Exception;
use MagicAdmin\Resource;

define("TIMEOUT", 10); 

class Magic {

    public $token;
    public $_resource;
    public $api_secret_key = null;
    public $user;

    public function __construct(
        $api_secret_key = null,
        $timeout = TIMEOUT,
    ) {
        $this->api_secret_key = $api_secret_key;
        $this->token = new \MagicAdmin\Resource\Token();
        $this->user = new \MagicAdmin\Resource\User($this->api_secret_key, $timeout);
    } 
}
