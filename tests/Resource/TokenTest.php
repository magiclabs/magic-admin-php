<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class TokenTest extends TestCase
{
    public $token;

    protected function setUp()
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

    protected function setUp()
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
