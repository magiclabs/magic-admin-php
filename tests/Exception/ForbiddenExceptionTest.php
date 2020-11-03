<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ForbiddenExceptionTest extends TestCase
{
    public $forbiddenException;

    protected function setUp()
    {
        $this->forbiddenException = new MagicAdmin\Exception\ForbiddenException(
            'Magic is amazing',
            'Magic is good',
            401,
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
        static::assertSame('MagicAdmin\\Exception\\ForbiddenException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=401)', $this->forbiddenException->getRepr());
    }
}
