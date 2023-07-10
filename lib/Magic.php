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
    public $client_id;

    public function __construct(
        $api_secret_key = null,
        $timeout = TIMEOUT,
        $retries = RETRIES,
        $backoff_factor = BACKOFF_FACTOR,
        $client_id = null
    ) {
        $this->api_secret_key = $api_secret_key;
        $request_client = new \MagicAdmin\HttpClient($api_secret_key, $timeout, $retries, $backoff_factor);
        if ($client_id != null) {
          $this->client_id = $client_id;
        } else {
          $this->client_id = $this->_get_client_id($request_client);
        }
        $this->token = new \MagicAdmin\Resource\Token($this->client_id);
        $this->user = new \MagicAdmin\Resource\User(
            $request_client,
            $this->token
        );
    }

    public function _set_platform($platform)
    {
        $this->user->_set_platform($platform);
    }

    public function _get_client_id($request_client)
    {
        $response = $request_client->request('get', '/v1/admin/client/get', []);

        return $response->data->client_id;
    }
}
