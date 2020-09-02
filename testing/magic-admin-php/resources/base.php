<?php

require_once(MAGIC_PACKAGE_PATH.'/config.php');
require_once(MAGIC_PACKAGE_PATH.'/http_client.php');
 

class ResourceComponent {

    public $_base_url = $base_url;
    public $_request_client;

    public function setup_request_client($retries, $timeout, $backoff_factor) {
        $this->_request_client = RequestsClient($retries, $timeout, $backoff_factor);
    }

    public function _construct_url($url_path) {
        return $this->_base_url . $url_path;
    }

    public function request($method, $url_path, $params=null, $data=null) {
        return $this->_request_client->request(
            lowercase($method),
            $this->_construct_url($url_path),
            $params,
            $data
        );
    }
}

