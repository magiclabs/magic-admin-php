<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class DidTokenTest extends TestCase
{
    private $public_address;
    private $issuer;

    protected function setUp(): void
    {
        $this->public_address = '0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
        $this->issuer = 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2';
    }

    public function testParsePublicAddressFromIssuer()
    {
        static::assertSame($this->public_address, \MagicAdmin\Util\DidToken::parse_public_address_from_issuer($this->issuer));
    }

    public function testConstructIssuerWithPublicAddress()
    {
        static::assertSame('did:ethr:' . $this->public_address, \MagicAdmin\Util\DidToken::construct_issuer_with_public_address($this->public_address));
    }
}
