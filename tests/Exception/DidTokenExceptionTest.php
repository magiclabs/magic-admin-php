<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class DidTokenExceptionTest extends TestCase
{
    public $dIDTokenException;

    protected function setUp(): void
    {
        $this->dIDTokenException = new MagicAdmin\Exception\DIDTokenException('Magic is amazing');
    }

    public function testGetRepr()
    {
        static::assertSame('MagicAdmin\\Exception\\DIDTokenException(message=Magic is amazing)', $this->dIDTokenException->getRepr());
    }
}
