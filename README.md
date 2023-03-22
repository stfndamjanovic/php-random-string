# Random string generator for PHP

[![Tests](https://img.shields.io/github/actions/workflow/status/stfndamjanovic/php-random-string/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/stfndamjanovic/php-random-string/actions/workflows/run-tests.yml)

Description

## Installation

You can install the package via composer:

```bash
composer require stfn/php-random-string
```

## Usage

Simple example without any configuration.

```php
use Stfn\RandomString\RandomString;

$string = RandomString::new()->generate();

echo $string; // Output: RIKdjFzuDaN12RiJ
```

If you want to generate string consist of numbers only, you can do it like this:
```php
use Stfn\RandomString\StringConfig;
use Stfn\RandomString\RandomString;

$config = StringConfig::make()->length(6)->numbersOnly();

$string = RandomString::fromConfig($config)->generate();

echo $string; // Output: 649432
```

Or you can use your custom charset for generating random string:
```php
use Stfn\RandomString\StringConfig;
use Stfn\RandomString\RandomString;

$config = StringConfig::make()->charset("ABCD1234");

$string = RandomString::fromConfig($config)->generate();

echo $string; // Output: 3B41B32C2A12A3A1
```

## Testing

```bash
composer test
```

## Security

While the RandomString class is designed to generate random and unpredictable string, it is important to note that it is not a cryptographically secure hash function and should not be used for sensitive applications such as password hashing or cryptographic key generation.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Stefan Damjanovic](https://github.com/stfndamjanovic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
