# Magic Admin PHP

## Requirements

PHP 5.6.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require magiclabs/magic-admin-php

```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/magiclabs/magic-admin-php). Then, to use the bindings, include the `magic-init.php` file.

```php
require_once('/path/to/magic-admin-php/init.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php)
-   [`gmp`](https://www.php.net/manual/en/book.gmp.php)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

Simple usage for login:

```php
  $did_token = \MagicAdmin\Util\parse_authorization_header_value(
    $authorization
  );

  if ($did_token == null) {
    throw new \MagicAdmin\Exception\BadRequestException(
      'Authorization header is missing or header value is invalid'
    );
  }

  $magic = new \MagicAdmin\Magic('YOUR_SECRET_API_KEY');
  
  try {
    $magic->token->validate($did_token);
    $issuer = $magic->token->get_issuer($did_token);
    $user_meta = $magic->user->get_metadata_by_issuer($issuer);
  } catch (Exception $e) {
    throw new \MagicAdmin\Exception\DIDTokenException(
      'DID Token is invalid: ' . $e->getMessage()
    ); 
  }
  # Call your appilication logic to load the user.
  /**
  $user_info = $logic->user->load_by($email)
    
  if ($user_info->issuer != $issuer) {
    throw new \MagicAdmin\Exception\UnAuthorizedException('UnAuthorized user login');
  }
  
  echo $user_info;
  **/
```

Simple usage for logout:

```php
  $did_token = \MagicAdmin\Util\parse_authorization_header_value(
    $authorization
  );

  if ($did_token == null) {
    throw new \MagicAdmin\Exception\BadRequestException(
      'Authorization header is missing or header value is invalid'
    );
  }

  $magic = new \MagicAdmin\Magic('YOUR_SECRET_API_KEY');
  
  try {
    $magic->token->validate($did_token);
    $issuer = $magic->token->get_issuer($did_token);
    $user_meta = $magic->user->get_metadata_by_issuer($issuer);
  } catch (Exception $e) {
    throw new \MagicAdmin\Exception\DIDTokenException(
      'DID Token is invalid: ' . $e->getMessage()
    ); 
  }


  # Call your appilication logic to load the user.
  /**
  $user_info = $logic->user->load_by($email)
    
  if ($user_info->issuer != $issuer) {
    throw new \MagicAdmin\Exception\UnAuthorizedError('UnAuthorized user login');
  }
  **/
```

