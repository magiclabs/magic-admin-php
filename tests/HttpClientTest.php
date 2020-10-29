<?php


//use MagicAdmin;
use MagicAdmin\Exception;
use PHPUnit\Framework\TestCase;

class RequestsClientTest extends TestCase {

  public $requestsClient;

  public function setUp() {
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 0.02;
    $api_secret_key = 'magic_admin';

    $this->requestsClient = new \MagicAdmin\RequestsClient($api_secret_key, $timeout, $retries, $backoff_factor);
  }

  public function test_retrieves() {
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 0.02;
    $api_secret_key = 'magic_admin';

    $this->assertEquals($this->requestsClient->_timeout, $timeout);
    $this->assertEquals($this->requestsClient->_retries, $retries);
    $this->assertEquals($this->requestsClient->_backoff_factor, $backoff_factor);
    $this->assertEquals($this->requestsClient->_api_secret_key, $api_secret_key);
  }

  public function test_get_version() {
    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('get_version')->willReturn('1.0.0');

    $this->assertEquals($mock->get_version(), '1.0.0');
  }

  public function test_get_user_agent() { 
    $expected_array = array();
    $expected_array[] = 'language: php';
    $expected_array[] = 'sdk_version: ' . $this->requestsClient->get_version();
    $expected_array[] = 'publisher: magic';
    $expected_array[] = 'http_lib: Magic';

    $this->assertEquals($this->requestsClient->_get_user_agent(), $expected_array);
  }

  public function test_get_request_headers() {
    $api_secret_key = "magic_admin";

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('_get_user_agent')
         ->willReturn(
            array(
              'language: php', 'sdk_version: ' . $this->requestsClient->get_version(), 'publisher: magic', 'http_lib: Magic'
            )
          );
    $expected_headers = array();
    $expected_headers[] = 'X-Magic-Secret-Key: ' .$api_secret_key;
    $expected_headers[] = 'User-Agent: ' . json_encode($mock->_get_user_agent());

    $this->assertEquals($this->requestsClient->_get_request_headers(), $expected_headers);
  } 

  public function test_parse_and_convert_to_api_response() {
    $resp_content = json_encode(array('data' => "magic_admin"));
    $status_code = 200;
    $method = "post";
    $request_params = "magic request_params";
    $request_data = "magic rquest_data";

    $result = $this->requestsClient->_parse_and_convert_to_api_response($resp_content, $status_code, $method, $request_params, $request_data);

    $this->assertEquals($result->content, json_decode($resp_content));
    $this->assertEquals($result->status_code, $status_code);
    $this->assertEquals($result->data, json_decode($resp_content)->data);
  }

  public function test_check_retry() {
    // check with retry number
    $this->assertEquals($this->requestsClient->check_retry(null, 200, 3), false); 
    // check with error
    $this->assertEquals($this->requestsClient->check_retry(CURLE_OPERATION_TIMEOUTED, 200, 1), true);
    $this->assertEquals($this->requestsClient->check_retry(CURLE_COULDNT_CONNECT, 200, 1), true);
    // check with http code
    $this->assertEquals($this->requestsClient->check_retry(null, 409, 1), true);
    $this->assertEquals($this->requestsClient->check_retry(null, 500, 1), true);
    $this->assertEquals($this->requestsClient->check_retry(null, 200, 1), false);
  }

  public function test_post_not_found_request() {    
    $method = "post";
    $url = "/v2/admin/auth/user/path";
    $params = "params";
    $data = "data";

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params, $data)
         ->willReturn(
            array(
              '{"data":{},"error_code":"NOT_FOUND","message":"The requested URL was not found on the server. If you entered the URL manually please check your spelling and try again.","status":"failed"}',
              404
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

    $this->expectException( MagicAdmin\Exception\ApiException::class);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, $data);
  }

  public function test_post_forbidden_request() {
    $method = "post";
    $url = "/path";
    $params = "params";
    $data = "data";

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params, $data)
         ->willReturn(
            array(
              "<html><head><title>403 Forbidden</title></head><body><center><h1>403 Forbidden</h1></center></body></html>",
              403
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

    $this->expectException( MagicAdmin\Exception\ForbiddenException::class);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, $data); 
  }

  public function test_post_unauthorized_request() {
    $method = "post";
    $url = "/v2/admin/auth/user/logout";
    $params = null;
    $data = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params, $data)
         ->willReturn(
            array(
              '{"data":{},"error_code":"UNAUTHORIZED","message":"Please try again.","status":"failed"}',
              401
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

    $this->expectException( MagicAdmin\Exception\AuthenticationException::class);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, $data); 
  }

  public function test_post_too_many_request() {
    $method = "post";
    $url = "/v2/admin/auth/user/logout";
    $params = null;
    $data = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params, $data)
         ->willReturn(
            array(
              '{"data":{},"error_code":"TOO_MANY_REQUEST","message":"Please try again.","status":"failed"}',
              429
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

    $this->expectException( MagicAdmin\Exception\RateLimitException::class);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, $data); 
  }

  public function test_post_invalid_key_request() {
    $method = "post";
    $url = "/v2/admin/auth/user/logout";
    $params = null;
    $data = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params, $data)
         ->willReturn(
            array(
              '{"data":{},"error_code":"INVALID_API_KEY","message":"Given API key is invalid. Please try again.","status":"failed"}',
              400
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

    $this->expectException( MagicAdmin\Exception\BadRequestException::class);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, $data);
  }

  public function test_get_invalid_key_request() {
    $method = "get";
    $url = "/v1/admin/auth/user/get";
    $params = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params)
         ->willReturn(
            array(
              '{"data":{},"error_code":"INVALID_API_KEY","message":"Given API key is invalid. Please try again.","status":"failed"}',
              400
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params);

    $this->expectException( MagicAdmin\Exception\BadRequestException::class);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, null);
  }

  public function test_get_malformed_issuer_request() {
    $method = "get";
    $url = "/v1/admin/auth/user/get";
    $params = array('issuer'=> 'magic_admin');

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params)
         ->willReturn(
            array(
              '{"data":{},"error_code":"MALFORMED_DID_ISSUER","message":"Given id (magic_admin) is malformed.","status":"failed"}',
              400
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params);

    $this->expectException( MagicAdmin\Exception\BadRequestException::class);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, null);
  }

  public function test_get_good_request() {
    $method = "get";
    $url = "/v1/admin/auth/user/get";
    $params = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');

    $mock = $this->createMock(\MagicAdmin\RequestsClient::class);
    $mock->method('api_request')
         ->with($method, $url, $params)
         ->willReturn(
            array(
              '{"data":{"email":"test@user.com","issuer":"did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4","public_address":"0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"},"error_code":"","message":"","status":"ok"}',
              200
            )
          );

    list($content, $status_code) = $mock->api_request($method, $url, $params);

    $result = $this->requestsClient->_parse_and_convert_to_api_response($content, $status_code, $method, $params, null);

    $this->assertEquals($result->data->issuer, 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');
    $this->assertEquals($result->content->error_code, '');
    $this->assertEquals($result->content->message, '');
    $this->assertEquals($result->content->status, 'ok');
  }
}
