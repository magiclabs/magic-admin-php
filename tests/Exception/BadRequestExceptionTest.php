<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class BadRequestExceptionTest extends TestCase
{
    public $badRequestException;

    protected function setUp(): void
    {
        $this->badRequestException = new MagicAdmin\Exception\BadRequestException(
            'Magic is amazing',
            'Magic is good',
            400,
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
        static::assertSame('MagicAdmin\\Exception\\BadRequestException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=400)', $this->badRequestException->getRepr());
    }
}
