<?php

require_once __DIR__ . '../../init.php';

//use MagicAdmin;
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

    protected function setUp(): void
    {
        $this->api_secret_key = 'magic_admin';
        $this->timeout = 10;
        $this->retries = 3;
        $this->backoff_factor = 0.02;
        $this->magic = new \MagicAdmin\Magic($this->api_secret_key, $this->timeout, $this->retries, $this->backoff_factor);
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
}
