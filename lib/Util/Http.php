<?php

namespace MagicAdmin\Util;

\define('AUTHORIZATION_PATTERN', '/Bearer\s(\S+)/');

class Http
{
    public static function parse_authorization_header_value($header_value)
    {
        if (!empty($header_value)) {
            if (\preg_match(AUTHORIZATION_PATTERN, $header_value, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
