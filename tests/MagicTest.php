<?php

require_once __DIR__ . '../../init.php';

//use MagicAdmin;
use MagicAdmin\HttpClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class MagicTest extends TestCase
{
    public $magic;
    public $api_secret_key;
    public $timeout;
    public $retries;
    public $backoff_factor;
    public $client_id = "client_id";
    public $mockedHttpClient;

  protected function setUp(): void
    {
        $this->api_secret_key = 'magic_admin';
        $this->timeout = 10;
        $this->retries = 3;
        $this->backoff_factor = 0.02;
        $this->magic = new \MagicAdmin\Magic($this->api_secret_key, $this->timeout, $this->retries, $this->backoff_factor, $this->client_id);
        $this->mockedHttpClient = $this->createMock(HttpClient::class);
    }

    public function testRetrievesApiSecretKey()
    {
      static::assertSame($this->magic->api_secret_key, $this->api_secret_key);
    }

    public function testRetrievesTimeout()
    {
        static::assertSame($this->magic->user->request_client->_timeout, $this->timeout);
    }

    public function testRetrievesRetries()
    {
        static::assertSame($this->magic->user->request_client->_retries, $this->retries);
    }

    public function testRetrievesBackoffFactor()
    {
        static::assertSame($this->magic->user->request_client->_backoff_factor, $this->backoff_factor);
    }

    public function testRetrievesClientId()
    {
      static::assertSame($this->magic->client_id, $this->client_id);
    }

    public function testRetrievesClientIdFromMagic()
    {
      $clientId = 'test_client_id';
      $this->mockedHttpClient->method('request')
        ->willReturn((object)[
          'data' => (object)[
            'client_id' => $clientId
          ]
        ]);
      static::assertSame($clientId, $this->magic->_get_client_id($this->mockedHttpClient));
    }

}
