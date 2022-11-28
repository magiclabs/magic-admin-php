<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class RateLimitingExceptionTest extends TestCase
{
    public $rateLimitException;

    protected function setUp(): void
    {
        $this->rateLimitException = new MagicAdmin\Exception\RateLimitingException(
            'Magic is amazing',
            'Magic is good',
            429,
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
        static::assertSame('MagicAdmin\\Exception\\RateLimitingException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=429)', $this->rateLimitException->getRepr());
    }
}
