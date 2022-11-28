<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class TokenTest extends TestCase
{
    public $token;

    protected function setUp(): void
    {
        $this->token = new \MagicAdmin\Resource\Token();
    }

    public function testCheckRequiredFields()
    {
        $claim = [
            'iat' => 1586764270,
            'ext' => 11173528500,
            'iss' => 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2',
            'sub' => 'NjrA53ScQ8IV80NJnx4t3Shi9-kFfF5qavD2Vr0d1dc=',
            'aud' => 'did:magic:731848cc-084e-41ff-bbdf-7f103817ea6b',
            'nbf' => 1586764270,
            'tid' => 'ebcc880a-ffc9-4375-84ae-154ccd5c746d',
            'add' => '0x84d6839268a1af9111fdeccd396f303805dca2bc03450b7eb116e2f5fc8c5a722d1fb9af233aa73c5c170839ce5ad8141b9b4643380982da4bfbb0b11284988f1b',
        ];
        static::assertSame($this->token->_check_required_fields($claim), null);
    }

    public function testCheckRequiredFieldsMissing()
    {
        $claim = [
            'iat' => 1586764270,
            'ext' => 11173528500,
            'iss' => 'did:ethr:0x4B73C58370AEfcEf86A6021afCDe5673511376B2',
            'sub' => 'NjrA53ScQ8IV80NJnx4t3Shi9-kFfF5qavD2Vr0d1dc=',
            'aud' => 'did:magic:731848cc-084e-41ff-bbdf-7f103817ea6b',
        ];

        try {
            $this->token->_check_required_fields($claim);
        } catch (\MagicAdmin\Exception\DIDTokenException $e) {
            static::assertSame($e->getErrorMessage(), 'DID token is missing required field(s):["nbf","tid"]');
        }
    }
}

/**
 * @internal
 * @coversNothing
 */
final class TokenDecodeTest extends TestCase
{
    public $token;

    protected function setUp(): void
    {
        $this->token = new \MagicAdmin\Resource\Token();
    }

    public function testDecodeRaisesErrorIfDidTokenIsMalformed()
    {
        $did_token = 'magic_token'; // did token is malformed

        try {
            $this->token->decode($did_token);
        } catch (\MagicAdmin\Exception\DIDTokenException $e) {
            static::assertSame($e->message, 'DID token is malformed. It has to be a based64 encoded JSON serialized string. DIDTokenException(<empty message>).');
        }
    }

    public function testDecodeRaisesErrorIfDidTokenIsMissingParts()
    {
        $did_token = 'WyJ7XCJpYXRcIjoxNjAwOTYxNDgyLFwiZXh0XCI6MTYwMDk2MjM4MixcImlzc1wiOlwiZGlkOmV0aHI6MHhhYkE1M2JkMjJiMjY3M0M2YzQyZmZBMTFDMjUxQjQ1RDhDY0JlNGE0XCIsXCJzdWJcIjpcIlFrQl82dFhQRWFxRjktLTFGU08yMTZGZnRDLW9EVFJadG5zNmxScWZiYjA9XCIsXCJhdWRcIjpcImRpZDptYWdpYzpjODEwZTZjYi1hMWNlLTQyZTgtOWU5NC1iOWExZjc5ZTIzMjZcIixcImFkZFwiOlwiMHhiMjQ4MWY5ZWNlNDY4YWExN2I1YTk0M2VmOTQwNjNiY2E0MDczMjYxZjBmYzE4NjEzNDk4MTg0OWIzNmIyOTk1N2M4ZTA0M2NhNGE2MzE3ZjdmM2IyOWQ0NGYxMDhmMTg3ZDBmOTM2YjFjMjE3YWEzNGZkMjA4MWQ2NTdkMzRmMDFjXCJ9Il0='; // proof is missing

        try {
            $this->token->decode($did_token);
        } catch (\MagicAdmin\Exception\DIDTokenException $e) {
            static::assertSame($e->message, 'DID token is malformed. It has to have two parts [proof, claim].');
        }
    }

    public function testDecodeRaisesErrorIfClaimIsNotJsonSerializable()
    {
        $did_token = 'YXJyYXkgKAogIDAgPT4gJzB4MTIzMjRjNjFlYTFkMjQ1ZjVmNmFlYTc5ODQ5Y2NjMDM5ZjllMTU4MjcyOWQyODZiNmM4YTZkNjE0OWUyOTgwNDQyMzA3NDY4NWNmYThiOGFlMGJhMzMwOTI0NjMyMTg4Y2IyNmM3NjkwNmQ0MTY2OTg3ZDczZDgyOWI4NTJjNzgxYicsCiAgMSA9PiAneyJpYXQiOjE2MDA5NjE0ODIsImV4dCI6MTYwMDk2MjM4MiwiaXNzIjoiZGlkOmV0aHI6MHhhYkE1M2JkMjJiMjY3M0M2YzQyZmZBMTFDMjUxQjQ1RDhDY0JlNGE0Iiwic3ViIjoiUWtCXzZ0WFBFYXFGOS0tMUZTTzIxNkZmdEMtb0RUUlp0bnM2bFJxZmJiMD0iLCJhdWQiOiJkaWQ6bWFnaWM6YzgxMGU2Y2ItYTFjZS00MmU4LTllOTQtYjlhMWY3OWUyMzI2IiwibmJmIjoxNjAwOTYxNDgyLCJ0aWQiOiI0MzNjYmFlYy04YTlhLTQ5N2UtOTlkNy1mMjViYTdkNjBjMzEiLCJhZGQiOiIweGIyNDgxZjllY2U0NjhhYTE3YjVhOTQzZWY5NDA2M2JjYTQwNzMyNjFmMGZjMTg2MTM0OTgxODQ5YjM2YjI5OTU3YzhlMDQzY2E0YTYzMTdmN2YzYjI5ZDQ0ZjEwOGYxODdkMGY5MzZiMWMyMTdhYTM0ZmQyMDgxZDY1N2QzNGYwMWMifScsCik='; // Not json serialized

        try {
            $this->token->decode($did_token);
        } catch (\MagicAdmin\Exception\DIDTokenException $e) {
            static::assertSame($e->message, 'DID token is malformed. Given claim should be a JSON serialized string. DIDTokenException(<empty message>).');
        }
    }
}

/**
 * @internal
 * @coversNothing
 */
final class TokenValidateTest extends TestCase
{
    public $token;

    protected function setUp(): void
    {
        $this->token = new \MagicAdmin\Resource\Token();
    }

    /**
     *  @doesNotPerformAssertions
     */
    public function testValidate()
    {
        $valid_did_token = 'WyIweGUwMjQzNTVlNDI5ZGNhZDM1MTdhZDk5ZWEzNDEwYWJmZDQ1YjBiNjM5OGIwNjY1NGRiYTQxNzljODdlMTYyNzgxNTc1YjA5ODFjNjU4ZjcwMjYwZTQ5MjMwZGE5NDg4YTA0ZDk5NzBlYjM4ZTZmZGRlY2Q2NTA5YTAyN2IwOGI5MWIiLCJ7XCJpYXRcIjoxNTg1MDExMjA0LFwiZXh0XCI6MTkwMDQxMTIwNCxcImlzc1wiOlwiZGlkOmV0aHI6MHhCMmVjOWI2MTY5OTc2MjQ5MWI2NTQyMjc4RTlkRkVDOTA1MGY4MDg5XCIsXCJzdWJcIjpcIjZ0RlhUZlJ4eWt3TUtPT2pTTWJkUHJFTXJwVWwzbTNqOERReWNGcU8ydHc9XCIsXCJhdWRcIjpcImRpZDptYWdpYzpmNTQxNjhlOS05Y2U5LTQ3ZjItODFjOC03Y2IyYTk2YjI2YmFcIixcIm5iZlwiOjE1ODUwMTEyMDQsXCJ0aWRcIjpcIjJkZGY1OTgzLTk4M2ItNDg3ZC1iNDY0LWJjNWUyODNhMDNjNVwiLFwiYWRkXCI6XCIweDkxZmJlNzRiZTZjNmJmZDhkZGRkZDkzMDExYjA1OWI5MjUzZjEwNzg1NjQ5NzM4YmEyMTdlNTFlMGUzZGYxMzgxZDIwZjUyMWEzNjQxZjIzZWI5OWNjYjM0ZTNiYzVkOTYzMzJmZGViYzhlZmE1MGNkYjQxNWU0NTUwMDk1MmNkMWNcIn0iXQ==';

        $this->token->validate($valid_did_token);
    }

    public function testValidateRaisesErrorIfDidTokenHasInvalidSigner()
    {
        $invalid_signer_did_token = 'WyIweDBhNTk4NmE1NDdiMzNhMDAxODIxNmRiNjk0YzNiMDg3YTU3MTk1Nzg4ZTZmMDc2NDg4NzA2ZTQ3ZmFhNjFhYzMzZDczZTM4ZmM5ZDA0YzU2YWVmZWNiMTAxMDA4OGEwNmFlOWFiZTE5ZDIyYWQ4MzNiMDhhM2VlNWNmZWM5ZDQ0MWMiLCJ7XCJpYXRcIjoxNTg1MDEwODIxLFwiZXh0XCI6MTkwMDQxMDgyMSxcImlzc1wiOlwiXFxcImRpZDpldGhyOjB4MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMFxcXCJcIixcInN1YlwiOlwiNnRGWFRmUnh5a3dNS09PalNNYmRQckVNcnBVbDNtM2o4RFF5Y0ZxTzJ0dz1cIixcImF1ZFwiOlwiZGlkOm1hZ2ljOjMzZjAxNGVlLTNkZDUtNGRmZi1iYzE2LTgxNTU3MTFiN2UwMlwiLFwibmJmXCI6MTU4NTAxMDgyMSxcInRpZFwiOlwiOGEzYjdkZDUtZTFjZi00OTY1LWFlMmItZDIwZjE4OGU2ZWMyXCIsXCJhZGRcIjpcIjB4OTFmYmU3NGJlNmM2YmZkOGRkZGRkOTMwMTFiMDU5YjkyNTNmMTA3ODU2NDk3MzhiYTIxN2U1MWUwZTNkZjEzODFkMjBmNTIxYTM2NDFmMjNlYjk5Y2NiMzRlM2JjNWQ5NjMzMmZkZWJjOGVmYTUwY2RiNDE1ZTQ1NTAwOTUyY2QxY1wifSJd';

        try {
            $this->token->validate($invalid_signer_did_token);
        } catch (\MagicAdmin\Exception\DIDTokenException $e) {
            static::assertSame($e->getMessage(), 'Signature mismatch between "proof" and "claim". Please generate a new token with an intended issuer.');
        }
    }

    public function testValidateRaisesErrorIfDidTokenIsExpired()
    {
        $expired_did_token = 'WyIweGE3MDUzYzg3OTI2ZjMzZDBjMTZiMjMyYjYwMWYxZDc2NmRiNWY3YWM4MTg2MzUyMzY4ZjAyMzIyMGEwNzJjYzkzM2JjYjI2MmU4ODQyNWViZDA0MzcyZGU3YTc0NzMwYjRmYWYzOGU0ZjgwNmYzOTJjMTVkNzY2YmVkMjVlZmUxMWIiLCJ7XCJpYXRcIjoxNTg1MDEwODM1LFwiZXh0XCI6MTU4NTAxMDgzNixcImlzc1wiOlwiZGlkOmV0aHI6MHhCMmVjOWI2MTY5OTc2MjQ5MWI2NTQyMjc4RTlkRkVDOTA1MGY4MDg5XCIsXCJzdWJcIjpcIjZ0RlhUZlJ4eWt3TUtPT2pTTWJkUHJFTXJwVWwzbTNqOERReWNGcU8ydHc9XCIsXCJhdWRcIjpcImRpZDptYWdpYzpkNGMwMjgxYi04YzViLTQ5NDMtODUwOS0xNDIxNzUxYTNjNzdcIixcIm5iZlwiOjE1ODUwMTA4MzUsXCJ0aWRcIjpcImFjMmE4YzFjLWE4OWEtNDgwOC1hY2QxLWM1ODg1ZTI2YWZiY1wiLFwiYWRkXCI6XCIweDkxZmJlNzRiZTZjNmJmZDhkZGRkZDkzMDExYjA1OWI5MjUzZjEwNzg1NjQ5NzM4YmEyMTdlNTFlMGUzZGYxMzgxZDIwZjUyMWEzNjQxZjIzZWI5OWNjYjM0ZTNiYzVkOTYzMzJmZGViYzhlZmE1MGNkYjQxNWU0NTUwMDk1MmNkMWNcIn0iXQ==';

        try {
            $this->token->validate($expired_did_token);
        } catch (\MagicAdmin\Exception\DIDTokenException $e) {
            static::assertSame($e->getMessage(), 'Given DID token has expired. Please generate a new one.');
        }
    }

    public function testValidateRaisesErrorIfDidTokenCannotBeUsedYet()
    {
        $valid_future_marked_did_token = 'WyIweDkzZjRiNTViYzRlN2E1ZWJkZTdmMzVkYzczMWE5NWFmOGYwZjVlMWQyMWQ5ZDYwZWQxM2Y4YmYzMmNiN2UwOTQ1MDM0MGI1Y2IyNTIxODZkNWQ3OTFiOTAyODZhYmY1NzM3YzMxN2M5NzNhMmQzMGY0MWZmYmFlNGU0NTdmMjE4MWIiLCJ7XCJpYXRcIjoxNTkxOTE0NTgyLFwiZXh0XCI6MjIyMjcxNDU4MixcImlzc1wiOlwiZGlkOmV0aHI6MHg0YzMzMmQ5QzRhMmEwNjY1YzNmODg1MTU1YjlFOTFmZEIzMDBlRTc2XCIsXCJzdWJcIjpcIms4NUtaR09Ycl9vMTYxNGdFVGN6Yzlac0phTjV4cjF2TVFXSWhnbjQ1Slk9XCIsXCJhdWRcIjpcImRpZDptYWdpYzoyMWI4ZjRkZS02ZmIzLTQ0M2YtOGM0MC04ODcwODJjNDQ1MjNcIixcIm5iZlwiOjE5MDczMTQ1ODIsXCJ0aWRcIjpcIjVhMjhjMjQwLWRmYzYtNDg2Ni04ODk1LTVkYzBhOTVkNWJkN1wiLFwiYWRkXCI6XCIweGRlMmI1ODgyNjUyZGExOTY4YWNlZTIyYWUyNGI2OWYxNThlZjg1NDQzOGE0OTlmMThjZGZlZDU3MzEwOGIxNzExYjQ2OWQ3MzQ5NzdhNGQ4NGJlM2RiODc2OTBkZjFmZjk4MTVjN2Y3NDIxNjIxMGY4Y2JhMGJmYzQ2ZGIwYjhkMWNcIn0iXQ==';

        try {
            $this->token->validate($valid_future_marked_did_token);
        } catch (\MagicAdmin\Exception\DIDTokenException $e) {
            static::assertSame($e->getMessage(), 'Given DID token cannot be used at this time. Please check the "nbf" field and regenerate a new token with a suitable value.');
        }
    }
}
