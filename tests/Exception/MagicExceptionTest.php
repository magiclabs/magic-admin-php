<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class MagicExceptionTest extends TestCase
{
    public $magicException;

    protected function setUp(): void
    {
        $this->magicException = new MagicAdmin\Exception\MagicException('Magic is amazing');
    }

    public function testGetErrorMessage()
    {
        static::assertSame('Magic is amazing', $this->magicException->getErrorMessage());
    }

    public function testGetRepr()
    {
        static::assertSame('MagicAdmin\\Exception\\MagicException(message=Magic is amazing)', $this->magicException->getRepr());
    }
}
