<?php

namespace MagicAdmin; 

use MagicAdmin\Exception;
use MagicAdmin\Util;

define('API_MAGIC_BASE_URL', 'https://api.magic.link');

define('API_SECRET_KEY_MISSING_MESSAGE', 
  'API secret key is missing. Please specify ' . 
  'an API secret key when you instantiate the `Magic(api_secret_key=<KEY>)` ' . 
  'object or use the environment variable, `MAGIC_API_SECRET_KEY`. You can ' . 
  'get your API secret key from https://dashboard.magic.link. If you are having ' . 
  'trouble, please don\'t hesitate to reach out to us at support@magic.link'
);

class RequestsClient {

  public $_timeout;
  public $_retries;
  public $_backoff_factor;
  public $_base_url;
  public $_api_secret_key;
  public $ch;

  public function __construct($api_secret_key, $timeout, $retries, $backoff_factor) {

    $this->_api_secret_key = $api_secret_key;
    $this->_base_url = API_MAGIC_BASE_URL;
    $this->_timeout = $timeout;
    $this->_retries = $retries;
    $this->_backoff_factor = $backoff_factor;
  }

  public function _setup_curl() {

    $this->ch = curl_init();
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,   true                          );
    curl_setopt($this->ch, CURLOPT_FORBID_REUSE,     true                          );
    curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION,   true                          );  
    curl_setopt($this->ch, CURLOPT_MAXREDIRS,        10                            );
    curl_setopt($this->ch, CURLOPT_TIMEOUT,          $this->_timeout               );
    curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT,   10                            );
    curl_setopt($this->ch, CURLOPT_FAILONERROR,      false                         );
    curl_setopt($this->ch, CURLOPT_HTTPHEADER,       $this->_get_request_headers() );

  }

  public function _get_request_headers() {
    if ($this->_api_secret_key == null) {
      throw new \MagicAdmin\Exception\AuthenticationException(
        API_SECRET_KEY_MISSING_MESSAGE
      );
    }

    $headers = array();
    $headers[] = 'X-Magic-Secret-Key: ' . $this->_api_secret_key;
    $headers[] = 'User-Agent: ' . json_encode($this->_get_user_agent());
    return $headers;
  }

  public function _get_user_agent() {
    $user_agent = array();
    $user_agent[] = 'language: php';
    $user_agent[] = 'sdk_version: ' . $this->get_version();
    $user_agent[] = 'publisher: Magic Labs Inc.';
    $user_agent[] = 'http_lib: magic-admin-php';
    return $user_agent;
  }

  public function request($method, $url, $params=null, $data=null) {
    
    list($content, $status_code) = $this->api_request($method, $url, $params, $data);

    return $this->_parse_and_convert_to_api_response(
      $content,
      $status_code,
      $method,
      $params,
      $data
    );
  }

  public function api_request($method, $url, $params=null, $data=null) {
    try {
      if ($method == 'get') {
        $send_params = '';
        if (is_array($params)) {
          $send_params = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        } else {
          throw new \MagicAdmin\Exception\BadRequestException(
            'Query must be a string or array.'
          );
        }

        $this->_setup_curl();
        curl_setopt($this->ch, CURLOPT_URL,  $this->_base_url . $url .'?'.$send_params );

        $retries_number = 0;

        while(true) {
          $rcode = 0;
          $errno = 0;
          $info = null;

          $content = curl_exec($this->ch);

          if (false === $content) {
            $errno = curl_errno($this->ch);
          } else {
            $info = curl_getinfo($this->ch);
            $rcode = $info['http_code'];
          }

          $should_retry = $this->check_retry($errno, $rcode, $retries_number);
          if ($should_retry) {
            $retries_number++;
            usleep((int) ($this->_backoff_factor * 1000000));
          } else {
            break;
          }
        }
        
        curl_close($this->ch);

      } else if ($method == 'post') {

        $this->_setup_curl();
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($this->ch, CURLOPT_URL,  $this->_base_url . $url );
        $retries_number = 0;

        while(true) {

          $rcode = 0;
          $errno = 0;
          $info = null;

          $content = curl_exec($this->ch);

          if (false === $content) {
            $errno = curl_errno($this->ch);
          } else {
            $info = curl_getinfo($this->ch);
            $rcode = $info['http_code'];
          }

          $should_retry = $this->check_retry($errno, $rcode, $retries_number);
          if ($should_retry) {
            $retries_number++;
            usleep((int) ($this->_backoff_factor * 1000000));
          } else {
            break;
          }
        }
        curl_close($this->ch);
      }

    } catch (Exception $e) {
      throw new \MagicAdmin\Exception\ApiConnectionException(
        'Unexpected error thrown while communicating to Magic. ' .
        'Please reach out to support@magic.link if the problem continues. ' .
        'Error message: ' . __class__ . ' was raised - ' . $e->getMessage()
      );
    }

    return array($content, $rcode);
  }

  public function check_retry($errno, $rcode, $retries_number) {
    if ($retries_number >= $this->_retries) {
      return false;
    }

    // Retry on timeout-related problems (either on open or read).
    if ($errno === CURLE_OPERATION_TIMEOUTED || $errno === CURLE_COULDNT_CONNECT) {
      return true;
    }

    // 409 Conflict
    if ($rcode === 409) {
        return true;
    }

    // Retry on 500, 503, and other internal errors.
    if ($rcode >= 500) {
        return true;
    }

    return false;
  }

  public function _parse_and_convert_to_api_response($resp_content, $status_code, $method, $request_params, $request_data) { 
    
    $resp_data = json_decode($resp_content);

    if ( $status_code >= 200 && $status_code < 300 ) {
      $magic_response = new MagicResponse($resp_data, $resp_data->data, $status_code);
      return $magic_response;
    }

    if ($status_code == 429){
      throw new \MagicAdmin\Exception\RateLimitingException(
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
      throw new \MagicAdmin\Exception\BadRequestException(
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
      throw new \MagicAdmin\Exception\AuthenticationException(
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
      throw new \MagicAdmin\Exception\ForbiddenException(
        '',
        $resp_data,
        $status_code,
        $resp_data,
        $resp_data,
        $resp_data,
        $request_params,
        $request_data,
        $method
      );
    } else {
      throw new \MagicAdmin\Exception\ApiException(
        '',
        array_key_exists("status" , $resp_data )? $resp_data->status : null,
        $status_code,
        array_key_exists("data" , $resp_data )? $resp_data->data : null,
        array_key_exists("message" , $resp_data )? $resp_data->message : null,
        array_key_exists("error_code" , $resp_data )? $resp_data->error_code : null,
        $request_params,
        $request_data,
        $method
      );      
    } 
  }

  public function get_version() {
    if (file_get_contents( MAGIC_ADMIN_PHP_PATH . '/VERSION')) {
      $version_number = file_get_contents( MAGIC_ADMIN_PHP_PATH . '/VERSION');
    } else {
      $version_number = '1.0.0';
    }
    return $version_number;    
  }
}
