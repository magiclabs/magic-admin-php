<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ApiConnectionExceptionTest extends TestCase
{
    public $apiConnectionException;

    protected function setUp()
    {
        $this->apiConnectionException = new MagicAdmin\Exception\ApiConnectionException(
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
        static::assertSame('MagicAdmin\\Exception\\ApiConnectionException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=500)', $this->apiConnectionException->getRepr());
    }
}
