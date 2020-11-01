<?php

namespace MagicAdmin\Util;

\define('DID_TOKEN_NBF_GRACE_PERIOD_S', 300);

class Time
{
    public static function epoch_time_now()
    {
        return \time();
    }

    public static function apply_did_token_nbf_grace_period($timestamp)
    {
        return $timestamp - DID_TOKEN_NBF_GRACE_PERIOD_S;
    }
}
