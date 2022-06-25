# Magic Admin PHP SDK

The Magic Admin PHP SDK provides convenient ways for developers to interact with Magic API endpoints and an array of utilities to handle [DID Token](https://docs.magic.link/tutorials/decentralized-id).

## Table of Contents

* [Documentation](#documentation)
* [Installation](#installation)
* [Quick Start](#quick-start)
* [Changelog](#changelog)
* [License](#license)

## Documentation
See the [Magic doc](https://docs.magic.link/admin-sdk/php)!

## Installation

### Composer

You can install the bindings via [Composer](https://getcomposer.org/). Run the following command:

```bash
composer require magiclabs/magic-admin-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once __DIR__ . '/vendor/autoload.php';
```

### Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/magiclabs/magic-admin-php). Then, to use the bindings, include the `init.php` file.

```php
require_once __DIR__ . '/path/to/magic-admin-php/init.php';
```

### Dependencies

The bindings require the following extensions in order to work properly. If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

- [`curl`](https://secure.php.net/manual/en/book.curl.php)
- [`gmp`](https://www.php.net/manual/en/book.gmp.php) or [`bcmath`](https://www.php.net/manual/en/book.bc.php) see below

For optimal performance ensure that your platform has the `gmp` extension installed. If your platform does not support `gmp` then `bcmath` may be used as an alternative, but note that `bcmath` is significantly slower than `gmp`.

Since `gmp` is a required dependency you may need to use the `--ignore-platform-reqs` flag when runnining `composer install` on a platform without the `gmp` extension.

### Prerequisites

PHP 5.6.0 and later.

## Quick Start

Simple usage for login:

```php
  require_once __DIR__ . '/vendor/autoload.php';

  $did_token = \MagicAdmin\Util\Http::parse_authorization_header_value(
    $authorization_header
  );

  if ($did_token === null) {
    // DIDT is missing from the original HTTP request header. You can handle this by
    // remapping it to your application error.
  }

  $magic = new \MagicAdmin\Magic('<YOUR_API_SECRET_KEY>');

  try {
    $magic->token->validate($did_token);
    $issuer = $magic->token->get_issuer($did_token);
  } catch (\MagicAdmin\Exception\DIDTokenException $e) {
    // DIDT is malformed. You can handle this by remapping it
    // to your application error.
  }
```

### Configure Network Strategy

The `Magic` object also takes in `retries`, `timeout` and `backoff` as optional arguments at the object instantiation time so you can override those values for your application setup.

```php
$magic = new \MagicAdmin\Magic(
  '<YOUR_API_SECRET_KEY>',
  5,    // timeout
  3,    // retries
  0.01  // backoff
);
```

See more examples from [Magic PHP doc](https://docs.magic.link/admin-sdk/php/examples/user-signup).

## Development

Get [Composer](https://getcomposer.org/). For example, on Mac OS:

```bash
brew install composer
```

Install dependencies:

```bash
composer install
```

Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), then you can run the test suite:

```bash
./vendor/bin/phpunit tests/
```

Or to run an individual test file:

```bash
./vendor/bin/phpunit tests/MagicTest.php
```

The library uses [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) for code formatting.
PHP CS Fixer must be [installed globally](https://cs.symfony.com/doc/installation.html).
Code must be formatted before PRs are submitted. Run the formatter with:

```bash
php-cs-fixer fix -v --using-cache=no .
```

## Changelog

See [Changelog](./CHANGELOG.md)

## License

See [License](./LICENSE.txt)
