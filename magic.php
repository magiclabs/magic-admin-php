<?php

if (!defined('MAGIC_PACKAGE_FILE')) {
    define('MAGIC_PACKAGE_PATH', __DIR__);
}

require_once(MAGIC_PACKAGE_PATH.'/config.php');
require_once(MAGIC_PACKAGE_PATH.'/error.php');
require_once(MAGIC_PACKAGE_PATH.'/resources/token.php');
require_once(MAGIC_PACKAGE_PATH.'/resources/user.php');

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
        $this->token = new Token();
        $this->user = new User($this->api_secret_key, $retries, $timeout, $backoff_factor);
    }

    public function _set_api_secret_key($api_secret_key) {
        $this->api_secret_key = $api_secret_key;
    }
}