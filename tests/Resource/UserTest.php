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
        $this->magic_response = new \MagicAdmin\MagicResponse(
            (object) [
                'data' => (object) [
                    'email' => 'test@user.com',
                    'issuer' => $this->issuer,
                    'public_address' => $this->public_address,
                ],
                'error_code' => '',
                'message' => '',
                'status' => 'ok',
            ],
            (object) [
                'email' => 'test@user.com',
                'issuer' => $this->issuer,
                'public_address' => $this->public_address,
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
