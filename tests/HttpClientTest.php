<?php

use MagicAdmin;
use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class RequestsClientTest extends TestCase {

  public $requestsClient;

  public function setUp() {
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 0.2;
    $api_secret_key = 'magic_admin';

    $this->requestsClient = new \MagicAdmin\RequestsClient($api_secret_key, $timeout, $retries, $backoff_factor);
  }

  public function test_retrieves() {
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 0.2;
    $api_secret_key = 'magic_admin';

    $this->assertEquals($this->requestsClient->_timeout, $timeout);
    $this->assertEquals($this->requestsClient->_retries, $retries);
    $this->assertEquals($this->requestsClient->_backoff_factor, $backoff_factor);
    $this->assertEquals($this->requestsClient->_api_secret_key, $api_secret_key);
  }

  public function test_get_version() {
    $stub = $this->createStub(\MagicAdmin\RequestsClient::class);
    $stub->method('get_version')->willReturn('1.0.0');

    $this->assertEquals($stub->get_version(), '1.0.0');
  }

  public function test_get_user_agent() {

    $expected_array = array();
    $expected_array[] = 'language: php';
    $expected_array[] = 'sdk_version: 1.0.0';
    $expected_array[] = 'publisher: magic';
    $expected_array[] = 'http_lib: Magic';

    $this->assertEquals($this->requestsClient->_get_user_agent(), $expected_array);
  }

  public function test_get_request_headers() {
    $api_secret_key = "magic_admin";

    $stub = $this->createStub(\MagicAdmin\RequestsClient::class);
    $stub->method('_get_user_agent')
         ->willReturn(
            array(
              'language: php', 'adk_version: 1.0.0', 'publisher: magic', 'http_lib: Magic'
            )
          );
    $expected_headers = array();
    $expected_headers[] = 'X-Magic-Secret-Key: ' .$api_secret_key;
    $expected_headers[] = 'User-Agent: ' . json_encode($stub->_get_user_agent());

    $this->assertEquals($this->requestsClient->_get_request_headers(), $expected_header);
  }

  public function test_request() {
    $method = "post";
    $url = "/path";
    $params = "params";
    $data = "data";

    $result = $this->requestsClient->request($method, $url, $params, $data);

    $this->assertEquals($result->status_code, 200);
  }

  public function test_parse_and_convert_to_api_response() {
    $resp_content = object (array('data' => "magic_admin"));
    $resp_info = array('http_code'=> 200);
    $method = "post";
    $request_params = "magic request_params";
    $request_data = "magic rquest_data";

    $result = $this->requestsClient->_parse_and_convert_to_api_response($resp_content, $resp_info, $method, $request_params, $request_data);

    $this->assertEquals($result->content, json_decode($resp_content));
    $this->assertEquals($result->status_code, $resp_info['http_code']);
    $this->assertEquals($result->data, $resp_data->data);
  }
}
