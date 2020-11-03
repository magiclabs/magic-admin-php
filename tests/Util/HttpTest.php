<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class HttpTest extends TestCase
{
    public function testParseAuthorizationHeaderValue()
    {
        $expected = 'Bearer magic_admin';
        $malformed = 'wrong_format';
        static::assertSame('magic_admin', \MagicAdmin\Util\Http::parse_authorization_header_value($expected));

        static::assertNull(\MagicAdmin\Util\Http::parse_authorization_header_value($malformed));
    }
}
