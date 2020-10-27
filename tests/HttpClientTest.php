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
    $resp_info = array('http_code'=> 200);
    $method = "post";
    $request_params = "magic request_params";
    $request_data = "magic rquest_data";

    $result = $this->requestsClient->_parse_and_convert_to_api_response($resp_content, $resp_info, $method, $request_params, $request_data);

    $this->assertEquals($result->content, json_decode($resp_content));
    $this->assertEquals($result->status_code, $resp_info['http_code']);
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

  public function test_post_forbidden_request() {
    $method = "post";
    $url = "/path";
    $params = "params";
    $data = "data";    

    $this->expectException( MagicAdmin\Exception\ForbiddenException::class);
    $result = $this->requestsClient->request($method, $url, $params, $data);
  }

  public function test_post_request() {
    $method = "post";
    $url = "/v2/admin/auth/user/logout";

    $params = null;
    $data = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');

    $this->expectException( MagicAdmin\Exception\BadRequestException::class);
    $result = $this->requestsClient->request($method, $url, $params, $data);
  }

  public function test_get_key_bad_request() {
    $method = "get";
    $url = "/v1/admin/auth/user/get";
    $params = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');

    $this->expectException( MagicAdmin\Exception\BadRequestException::class);
    $result = $this->requestsClient->request($method, $url, $params);
  }

  public function test_get_issuer_bad_request() {
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 0.02;
    $api_secret_key = 'sk_test_6F832D5FB8382105';

    $test_request_client = new \MagicAdmin\RequestsClient($api_secret_key, $timeout, $retries, $backoff_factor);

    $method = "get";
    $url = "/v1/admin/auth/user/get";
    $params = array('issuer'=> 'magic_admin');

    $this->expectException( MagicAdmin\Exception\BadRequestException::class);
    $result = $test_request_client->request($method, $url, $params);
  }

  public function test_get_good_request() {
    $timeout = 10;
    $retries = 3;
    $backoff_factor = 0.02;
    $api_secret_key = 'sk_test_6F832D5FB8382105';

    $test_request_client = new \MagicAdmin\RequestsClient($api_secret_key, $timeout, $retries, $backoff_factor);

    $method = "get";
    $url = "/v1/admin/auth/user/get";
    $params = array('issuer'=> 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');
    $result = $test_request_client->request($method, $url, $params);
    $this->assertEquals($result->data->issuer, 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');
  }

  public function test_post_api_exception_request() {
    $method = "post";
    $url = "/v2/admin/auth/user/path";
    $params = "params";
    $data = "data";    

    $this->expectException( MagicAdmin\Exception\ApiException::class);
    $result = $this->requestsClient->request($method, $url, $params, $data);
  }
}
