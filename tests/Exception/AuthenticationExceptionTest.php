<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AuthenticationExceptionTest extends TestCase
{
    public $authenticationException;

    protected function setUp(): void
    {
        $this->authenticationException = new MagicAdmin\Exception\AuthenticationException(
            'Magic is amazing',
            'Magic is good',
            403,
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
        static::assertSame('MagicAdmin\\Exception\\AuthenticationException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=403)', $this->authenticationException->getRepr());
    }
}
