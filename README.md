# Random hash generator for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stfn/php-random-hash.svg?style=flat-square)](https://packagist.org/packages/stfn/random-hash)
[![Tests](https://img.shields.io/github/actions/workflow/status/stfn/php-random-hash/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/stfn/random-hash/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/stfn/php-random-hash.svg?style=flat-square)](https://packagist.org/packages/stfn/random-hash)

Description

## Installation

You can install the package via composer:

```bash
composer require stfn/php-random-hash
```

## Usage

```php
use Stfn\RandomHash\HashConfig;
use Stfn\RandomHash\RandomHash;

$config = HashConfig::make()
            ->length(6)
            ->numbersOnly()
            ->skip(function ($hash) {
                return in_array($hash, ['034522', '109487']);
            });

$hashes = RandomHash::make($config)->generate();
```

This example will skip 034522 and 109487 and generate 6 character hash consist of numbers only.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Stefan Damjanovic](https://github.com/stfndamjanovic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
