<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class UserTest extends TestCase
{
    public $user;
    private $issuer;
    private $public_address;
    private $wallet_type;
    private $wallet;
    private $wallets;

    protected function setUp(): void
    {
        $api_secret_key = 'magic_admin';
        $timeout = 10;
        $retries = 3;
        $backoff_factor = 0.02;
        $this->user = new \MagicAdmin\Resource\User(
            $api_secret_key,
            $timeout,
            $retries,
            $backoff_factor
        );
        $this->issuer = 'did:ethr:0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4';
        $this->public_address = '0xabA53bd22b2673C6c42ffA11C251B45D8CcBe4a4';
        $this->wallet_type = \MagicAdmin\Resource\Wallet::SOLANA;
        $this->wallet = (object) [
            'network'=> 'MAINNET',
            'public_address' => $this->public_address,
            'wallet_type' => $this->wallet_type
        ];
        $this->wallets = array($this->wallet);
        $this->magic_response = new \MagicAdmin\MagicResponse(
            (object) [
                'data' => (object) [
                    'email' => 'test@user.com',
                    'issuer' => $this->issuer,
                    'public_address' => $this->public_address,
                    'wallets' => $this->wallets,
                ],
                'error_code' => '',
                'message' => '',
                'status' => 'ok',
            ],
            (object) [
                'email' => 'test@user.com',
                'issuer' => $this->issuer,
                'public_address' => $this->public_address,
                'wallets' => $this->wallets,
            ],
            200
        );
    }

    public function testGetMetadataByIssuer()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_issuer')->with($this->issuer)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_issuer($this->issuer);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
    }

    public function testGetMetadataByIssuerAndWallet()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_issuer_and_wallet')->with($this->issuer, $this->wallet_type)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_issuer_and_wallet($this->issuer, $this->wallet_type);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
        static::assertSame($meta_data->data->wallets[0]->wallet_type, $this->wallet_type);
    }

    public function testGetMetadataByIssuerAndAnyWallet()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_issuer_and_wallet')->with($this->issuer, \MagicAdmin\Resource\Wallet::ANY)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_issuer_and_wallet($this->issuer, \MagicAdmin\Resource\Wallet::ANY);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
        static::assertSame(count($meta_data->data->wallets), 1);
    }

    public function testGetMetadataByPublicAddressAndWallet()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_public_address_and_wallet')->with($this->public_address, $this->wallet_type)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_public_address_and_wallet($this->public_address, $this->wallet_type);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
        static::assertSame($meta_data->data->wallets[0]->wallet_type, $this->wallet_type);
    }

    public function testGetMetadataByIssuerAndNoneWallet()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_issuer_and_wallet')->with($this->issuer, \MagicAdmin\Resource\Wallet::NONE)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_issuer_and_wallet($this->issuer, \MagicAdmin\Resource\Wallet::NONE);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
    }

    public function testGetMetadataByPublicAddress()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_public_address')->with($this->public_address)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_public_address($this->public_address);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
    }

    public function testGetMetadataByToken()
    {
        $did_token = 'magic_token';
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_token')->with($did_token)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_token($did_token);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
    }

    public function testGetMetadataByTokenAndWallet()
    {
        $did_token = 'magic_token';
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('get_metadata_by_token_and_wallet')->with($did_token, $this->wallet_type)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->get_metadata_by_token_and_wallet($did_token, $this->wallet_type);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
        static::assertSame($meta_data->data->wallets[0]->wallet_type, $this->wallet_type);
    }

    public function testLogoutByIssuer()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('logout_by_issuer')->with($this->issuer)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->logout_by_issuer($this->issuer);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
    }

    public function testLogoutByPublicAddress()
    {
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('logout_by_public_address')->with($this->public_address)->willReturn(
            $this->magic_response
        );

        $meta_data = $mock->logout_by_public_address($this->public_address);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
    }

    public function testLogoutByToken()
    {
        $did_token = 'magic_token';
        $mock = $this->createMock(\MagicAdmin\Resource\User::class);
        $mock->method('logout_by_token')
            ->with($did_token)
            ->willReturn($this->magic_response)
        ;

        $meta_data = $mock->logout_by_token($did_token);

        static::assertSame($meta_data->data->issuer, $this->issuer);
        static::assertSame($meta_data->data->public_address, $this->public_address);
    }
}
