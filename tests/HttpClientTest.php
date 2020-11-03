<?php

//use MagicAdmin;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class HttpClientTest extends TestCase
{
    public $requestsClient;

    protected function setUp()
    {
        $timeout = 10;
        $retries = 3;
        $backoff_factor = 0.02;
        $api_secret_key = 'magic_admin';

        $this->requestsClient = new \MagicAdmin\HttpClient(
            $api_secret_key,
            $timeout,
            $retries,
            $backoff_factor
        );
    }

    public function testRetrieves()
    {
        $timeout = 10;
        $retries = 3;
        $backoff_factor = 0.02;
        $api_secret_key = 'magic_admin';

        static::assertSame($this->requestsClient->_timeout, $timeout);
        static::assertSame($this->requestsClient->_retries, $retries);
        static::assertSame($this->requestsClient->_backoff_factor, $backoff_factor);
        static::assertSame($this->requestsClient->_api_secret_key, $api_secret_key);
    }

    public function testGetVersion()
    {
        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('get_version')->willReturn('1.0.0');

        static::assertSame($mock->get_version(), '1.0.0');
    }

    public function testGetUserAgent()
    {
        $expected_array = [];
        $expected_array[] = 'language: php';
        $expected_array[] = 'sdk_version: ' . $this->requestsClient->get_version();
        $expected_array[] = 'publisher: Magic Labs Inc.';
        $expected_array[] = 'http_lib: magic-admin-php';

        static::assertSame($this->requestsClient->_get_user_agent(), $expected_array);
    }

    public function testGetRequestHeaders()
    {
        $api_secret_key = 'magic_admin';

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('_get_user_agent')->willReturn(
            [
                'language: php',
                'sdk_version: ' . $this->requestsClient->get_version(),
                'publisher: Magic Labs Inc.',
                'http_lib: magic-admin-php',
            ]
        );
        $expected_headers = [];
        $expected_headers[] = 'X-Magic-Secret-Key: ' . $api_secret_key;
        $expected_headers[] = 'User-Agent: ' . \json_encode($mock->_get_user_agent());

        static::assertSame($this->requestsClient->_get_request_headers(), $expected_headers);
    }

    public function testParseAndConvertToApiResponse()
    {
        $resp_content = \json_encode(['data' => 'magic_admin']);
        $status_code = 200;
        $method = 'post';
        $request_params = 'magic request_params';
        $request_data = 'magic rquest_data';

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $resp_content,
            $status_code,
            $method,
            $request_params,
            $request_data
        );

        static::assertSame($result->content->data, \json_decode($resp_content)->data);
        static::assertSame($result->status_code, $status_code);
        static::assertSame($result->data, \json_decode($resp_content)->data);
    }

    public function testCheckRetry()
    {
        // check with retry number
        static::assertSame($this->requestsClient->check_retry(null, 200, 3), false);
        // check with error
        static::assertSame($this->requestsClient->check_retry(CURLE_OPERATION_TIMEOUTED, 200, 1), true);
        static::assertSame($this->requestsClient->check_retry(CURLE_COULDNT_CONNECT, 200, 1), true);
        // check with http code
        static::assertSame($this->requestsClient->check_retry(null, 409, 1), true);
        static::assertSame($this->requestsClient->check_retry(null, 500, 1), true);
        static::assertSame($this->requestsClient->check_retry(null, 200, 1), false);
    }

    public function testPostNotFoundRequest()
    {
        $method = 'post';
        $url = '/v2/admin/auth/user/path';
        $params = 'params';
        $data = 'data';

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params, $data)->willReturn(
            [
                '{"data":{},"error_code":"NOT_FOUND","message":"The requested URL was not found on the server. If you entered the URL manually please check your spelling and try again.","status":"failed"}',
                404,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

        $this->expectException(MagicAdmin\Exception\ApiException::class);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            $data
        );
    }

    public function testPostForbiddenRequest()
    {
        $method = 'post';
        $url = '/path';
        $params = 'params';
        $data = 'data';

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params, $data)->willReturn(
            [
                '{"data":{},"error_code":"UNAUTHORIZED","message":"Please try again.","status":"failed"}',
                403,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

        $this->expectException(MagicAdmin\Exception\ForbiddenException::class);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            $data
        );
    }

    public function testPostUnauthorizedRequest()
    {
        $method = 'post';
        $url = '/v2/admin/auth/user/logout';
        $params = null;
        $data = ['issuer' => 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4'];

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params, $data)->willReturn(
            [
                '{"data":{},"error_code":"UNAUTHORIZED","message":"Please try again.","status":"failed"}',
                401,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

        $this->expectException(MagicAdmin\Exception\AuthenticationException::class);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            $data
        );
    }

    public function testPostTooManyRequest()
    {
        $method = 'post';
        $url = '/v2/admin/auth/user/logout';
        $params = null;
        $data = ['issuer' => 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4'];

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params, $data)->willReturn(
            [
                '{"data":{},"error_code":"TOO_MANY_REQUEST","message":"Please try again.","status":"failed"}',
                429,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

        $this->expectException(MagicAdmin\Exception\RateLimitingException::class);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            $data
        );
    }

    public function testPostInvalidKeyRequest()
    {
        $method = 'post';
        $url = '/v2/admin/auth/user/logout';
        $params = null;
        $data = ['issuer' => 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4'];

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params, $data)->willReturn(
            [
                '{"data":{},"error_code":"INVALID_API_KEY","message":"Given API key is invalid. Please try again.","status":"failed"}',
                400,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params, $data);

        $this->expectException(MagicAdmin\Exception\BadRequestException::class);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            $data
        );
    }

    public function testGetInvalidKeyRequest()
    {
        $method = 'get';
        $url = '/v1/admin/auth/user/get';
        $params = ['issuer' => 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4'];

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params)->willReturn(
            [
                '{"data":{},"error_code":"INVALID_API_KEY","message":"Given API key is invalid. Please try again.","status":"failed"}',
                400,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params);

        $this->expectException(MagicAdmin\Exception\BadRequestException::class);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            null
        );
    }

    public function testGetMalformedIssuerRequest()
    {
        $method = 'get';
        $url = '/v1/admin/auth/user/get';
        $params = ['issuer' => 'magic_admin'];

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params)->willReturn(
            [
                '{"data":{},"error_code":"MALFORMED_DID_ISSUER","message":"Given id (magic_admin) is malformed.","status":"failed"}',
                400,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params);

        $this->expectException(MagicAdmin\Exception\BadRequestException::class);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            null
        );
    }

    public function testGetGoodRequest()
    {
        $method = 'get';
        $url = '/v1/admin/auth/user/get';
        $params = ['issuer' => 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4'];

        $mock = $this->createMock(\MagicAdmin\HttpClient::class);
        $mock->method('api_request')->with($method, $url, $params)->willReturn(
            [
                '{"data":{"email":"test@user.com","issuer":"did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4","public_address":"0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4"},"error_code":"","message":"","status":"ok"}',
                200,
            ]
        );

        list($content, $status_code) = $mock->api_request($method, $url, $params);

        $result = $this->requestsClient->_parse_and_convert_to_api_response(
            $content,
            $status_code,
            $method,
            $params,
            null
        );

        static::assertSame($result->data->issuer, 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4');
        static::assertSame($result->content->error_code, '');
        static::assertSame($result->content->message, '');
        static::assertSame($result->content->status, 'ok');
    }
}
