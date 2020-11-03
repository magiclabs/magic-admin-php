<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class TimeTest extends TestCase
{
    public function testEpochTimeNow()
    {
        static::assertSame(\time(), \MagicAdmin\Util\Time::epoch_time_now());
    }

    public function testApplyDidTokenNbfGracePeriod()
    {
        $timestamp = 8084;
        static::assertSame($timestamp - DID_TOKEN_NBF_GRACE_PERIOD_S, \MagicAdmin\Util\Time::apply_did_token_nbf_grace_period($timestamp));
    }
}
