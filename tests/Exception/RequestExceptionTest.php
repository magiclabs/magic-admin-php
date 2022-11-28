<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class RequestExceptionTest extends TestCase
{
    public $requestException;

    protected function setUp(): void
    {
        $this->requestException = new MagicAdmin\Exception\RequestException(
            'Magic is amazing',
            'Magic is good',
            500,
            ['magic' => 'link'],
            'Magic is good',
            'MAGIC_IS_GOOD',
            'a=b&b=c',
            ['magic' => 'link'],
            'post'
        );
    }

    public function testGetRepr()
    {
        static::assertSame('MagicAdmin\\Exception\\RequestException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=500)', $this->requestException->getRepr());
    }
}
