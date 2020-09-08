<?php

namespace MagicAdmin;

require_once(MAGIC_PACKAGE_PATH.'/version.php');
require_once(MAGIC_PACKAGE_PATH.'/config.php');
require_once(MAGIC_PACKAGE_PATH.'/error.php');
require_once(MAGIC_PACKAGE_PATH.'/response.php');

class RequestsClient {

    public $_retries;
    public $_timeout;
    public $_backoff_factor;
    public $_base_url;
    public $_api_secret_key;
    public $ch;

    public function __construct($api_secret_key, $retries, $timeout, $backoff_factor) {

        $this->_api_secret_key = $api_secret_key;
        $this->_base_url = api_magic_base_url;
        $this->_retries = $retries;
        $this->_timeout = $timeout;
        $this->_backoff_factor = $backoff_factor;
    }

    public function _setup_curl() {

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,   true                          );
        curl_setopt($this->ch, CURLOPT_USERAGENT,        $_SERVER['HTTP_USER_AGENT']   );
        curl_setopt($this->ch, CURLOPT_FORBID_REUSE,     true                          );
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION,   true                          );  
        curl_setopt($this->ch, CURLOPT_MAXREDIRS,        10                            );
        curl_setopt($this->ch, CURLOPT_TIMEOUT,          $this->_timeout                );
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT,   30                            );
        curl_setopt($this->ch, CURLOPT_FAILONERROR,      false                         );
        curl_setopt($this->ch, CURLOPT_HTTPHEADER,       $this->_get_request_headers() );

    }

    public function _get_request_headers() {
        if ($this->_api_secret_key == null) {
            $authenticationError = new AuthenticationError(
                api_secret_api_key_missing_message
            );
            echo $authenticationError->getErrorMessage();
        }

        $headers = array();
        $headers[] = 'X-Magic-Secret-Key: ' . $this->_api_secret_key;
        return $headers;
    }

    public function _get_user_agent() {
        $user_agent = array();
        $user_agent[] = 'language: php';
        $user_agent[] = 'sdk_version: ' . SDK_VERSION;
        $user_agent[] = 'publisher: magic';
        $user_agent[] = 'http_lib: ' . __class__;
        return $user_agent;
    }

    public function request($method, $url, $params=null, $data=null) {
        try {
            if ($method == 'get') {
                $send_params = '';
                foreach($params as $key=>$value) {
                    $send_params .= $key.'='.$value.'&';
                }
                $send_params = trim($send_params, '&');

                $this->_setup_curl();
                curl_setopt($this->ch, CURLOPT_URL,  $this->_base_url . $url .'?'.$send_params );
                $content = curl_exec($this->ch);
                $info = curl_getinfo($this->ch);
                curl_close($this->ch);

            } else if ($method == 'post') {

                $this->_setup_curl();
                curl_setopt($this->ch, CURLOPT_POST, true);
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($this->ch, CURLOPT_URL,  $this->_base_url . $url );
                $content = curl_exec($this->ch);
                $info = curl_getinfo($this->ch);
                curl_close($this->ch);
            }            

        } catch (Exception $e) {
            return $this->_handle_request_error($e);
        }

        return $this->_parse_and_convert_to_api_response(
            $content,
            $info,
            $method,
            $params,
            $data
        );
    }

    public function _parse_and_convert_to_api_response($resp_content, $resp_info, $method, $request_params, $request_data) {
        $status_code = $resp_info['http_code'];

        $resp_data = json_decode($resp_content);

        if ( $status_code >= 200 && $status_code < 300 ) {
            $magic_response = new MagicResponse($resp_data, $resp_data->data, $status_code);
            return $magic_response;
        }

        if ($status_code == 429){
            $error = new RateLimitingError(
                '',
                $resp_data->status,
                $status_code,
                $resp_data->data,
                $resp_data->message,
                $resp_data->error_code,
                $request_params,
                $request_data,
                $method
            );
        } else if ($status_code == 400) {
            $error = new BadRequestError(
                '',
                $resp_data->status,
                $status_code,
                $resp_data->data,
                $resp_data->message,
                $resp_data->error_code,
                $request_params,
                $request_data,
                $method
            );
        } else if ($status_code == 401) {
            $error = new AuthenticationError(
                '',
                $resp_data->status,
                $status_code,
                $resp_data->data,
                $resp_data->message,
                $resp_data->error_code,
                $request_params,
                $request_data,
                $method
            );
        } else if ($status_code == 403) {
            $error = new ForbiddenError(
                '',
                $resp_data->status,
                $status_code,
                $resp_data->data,
                $resp_data->message,
                $resp_data->error_code,
                $request_params,
                $request_data,
                $method
            );
        } else {
            $error = new APIError(
                '',
                $resp_data->status,
                $status_code,
                $resp_data->data,
                $resp_data->message,
                $resp_data->error_code,
                $request_params,
                $request_data,
                $method
            );
        }
        
        echo $error->getErrorMessage();
    }

    public function _handle_request_error($e) {

        $error_message = 'Unexpected error thrown while communicating to Magic. ' .
            'Please reach out to support@magic.link if the problem continues. ' .
            'Error message: ' . __class__ . ' was raised - ' . $e->getMessage();

        $apiConnectionError = new APIConnectionError($error_message);
        echo $apiConnectionError->getErrorMessage();
    }

    public function _micro_time_count() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}