<?php

// Define package root path
if (!\defined('MAGIC_ADMIN_PHP_PATH')) {
    \define('MAGIC_ADMIN_PHP_PATH', __DIR__);
}

// Magic
require __DIR__ . '/lib/Magic.php';

// Resources
require __DIR__ . '/lib/Resource/Token.php';
require __DIR__ . '/lib/Resource/User.php';

// HttpClient
require __DIR__ . '/lib/HttpClient.php';

// Response
require __DIR__ . '/lib/MagicResponse.php';

// Utilities
require __DIR__ . '/lib/Util/DidToken.php';
require __DIR__ . '/lib/Util/Http.php';
require __DIR__ . '/lib/Util/Time.php';

// Exceptions
require __DIR__ . '/lib/Exception/MagicException.php';
require __DIR__ . '/lib/Exception/DIDTokenException.php';
require __DIR__ . '/lib/Exception/RequestException.php';
require __DIR__ . '/lib/Exception/ApiConnectionException.php';
require __DIR__ . '/lib/Exception/RateLimitingException.php';
require __DIR__ . '/lib/Exception/BadRequestException.php';
require __DIR__ . '/lib/Exception/AuthenticationException.php';
require __DIR__ . '/lib/Exception/ForbiddenException.php';
require __DIR__ . '/lib/Exception/ApiException.php';
