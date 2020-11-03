<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ApiExceptionTest extends TestCase
{
    public $apiException;

    protected function setUp()
    {
        $this->apiException = new MagicAdmin\Exception\ApiException(
            'Magic is amazing',
            'Magic is good',
            503,
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
        static::assertSame('MagicAdmin\\Exception\\ApiException(message=Magic is amazing, http_error_code=MAGIC_IS_GOOD, http_code=503)', $this->apiException->getRepr());
    }
}
