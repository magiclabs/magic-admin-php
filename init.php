<?php  

// Define package root path
if (!defined('MAGIC_ADMIN_PHP_PATH')) {
	define('MAGIC_ADMIN_PHP_PATH', __dir__);
}

// Magic
require( __dir__ . '/lib/Magic.php' );

// Resources
require( __dir__ . '/lib/Resource/Token.php' );
require( __dir__ . '/lib/Resource/User.php' );

// HttpClient
require( __dir__ . '/lib/HttpClient.php' );

// Response
require( __dir__ . '/lib/Response.php' );

// Utilities
require( __dir__ . '/lib/Util/DidToken.php' );
require( __dir__ . '/lib/Util/Http.php' );
require( __dir__ . '/lib/Util/Time.php' );

// Exceptions
require( __dir__ . '/lib/Exception/MagicException.php' );
require( __dir__ . '/lib/Exception/DidTokenException.php' );
require( __dir__ . '/lib/Exception/ApiConnectionException.php' );
require( __dir__ . '/lib/Exception/RequestException.php' );
require( __dir__ . '/lib/Exception/RateLimitException.php' );
require( __dir__ . '/lib/Exception/BadRequestException.php' );
require( __dir__ . '/lib/Exception/AuthenticationException.php' );
require( __dir__ . '/lib/Exception/ForbiddenException.php' );
require( __dir__ . '/lib/Exception/ApiException.php' );
