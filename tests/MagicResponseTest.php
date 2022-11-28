<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class MagicResponseTest extends TestCase
{
    public $magicResponse;
    public $content = 'magic link';
    public $resp_data = 'magic is amazing';
    public $status_code = 200;

    protected function setUp(): void
    {
        $this->magicResponse = new \MagicAdmin\MagicResponse($this->content, $this->resp_data, $this->status_code);
    }

    public function testRetrievesContent()
    {
        static::assertSame($this->magicResponse->content, $this->content);
    }

    public function testRetrievesRespData()
    {
        static::assertSame($this->magicResponse->data, $this->resp_data);
    }

    public function testRetrievesStatusCode()
    {
        static::assertSame($this->magicResponse->status_code, $this->status_code);
    }
}
