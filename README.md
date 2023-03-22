# Random string generator for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stfn/php-random-string.svg?style=flat-square)](https://packagist.org/packages/stfn/php-random-string)
[![Tests](https://img.shields.io/github/actions/workflow/status/stfndamjanovic/php-random-string/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/stfndamjanovic/php-random-string/actions/workflows/run-tests.yml)

This package can be used to generate a random string based on your set of characters or predefined ones. You can configure string length, prefix, suffix, and count of strings, or skip some strings under certain conditions...

## Installation

You can install the package via composer:

```bash
composer require stfn/php-random-string
```

## Usage

### Basic
Simple example without any configuration.

```php
$string = RandomString::new()->generate(); // Output: RIKdjFzuDaN12RiJ
```

### Length definition

You can control the length of the string. By default, it's 16 characters.

```php
$string = RandomString::new(6)->generate(); // Output: dzGcot
````

### Predefined charset

If you want to generate a string consisting of numbers only, lowercase letters, or uppercase letters you can use predefined charsets.

```php
// Generate string that contains only numbers
$config = StringConfig::make()
            ->numbersOnly();

$string = RandomString::fromConfig($config)->generate(); // Output: 9387406871490781

// Generate string that contains only lowercase letters
$config = StringConfig::make()
            ->lowerCaseOnly();

$string = RandomString::fromConfig($config)->generate(); // Output: hvphyfmgnvbbajve

// Generate string that contains only uppercase letters
$config = StringConfig::make()
            ->upperCaseOnly();

$string = RandomString::fromConfig($config)->generate(); // Output: ZIVSUDQHAMDNQAYV
```

### Custom charset

Or you can use your custom charset for generating random string.

```php
$config = StringConfig::make()
            ->charset("ABCDEFG1234");

$string = RandomString::fromConfig($config)->generate(); // Output: 3B41B32C2A12A3A1
```

### Skipping

Sometimes you may want to generate a random string but under certain conditions. 
For example, give me a string that is not part of this array.

```php
$config = StringConfig::make()
            ->numbersOnly()
            ->length(6)
            ->skip(function ($string) {
                return in_array($string, ["025922", "104923"]);
            });

$string = RandomString::fromConfig($config)->generate(); // Output: 083712
```

### Prefix and Suffix

If you want to add a prefix or suffix to generated string, you can do it like this.

```php
use Stfn\RandomString\StringConfig;

$config = StringConfig::make()
            ->length(6)
            ->prefix("PRE_")
            ->suffix("_AFTER");

$string = RandomString::fromConfig($config)->generate(); // Output: PRE_rkM7Jl_AFTER
```

### Array of random strings

`RandomString` can generate more than just one string.

```php
$config = StringConfig::make()
            ->length(6)
            ->count(3);

$strings = RandomString::fromConfig($config)->generate();

// Output: ["ozBYeT", "BYjCtr", "Sw7O5b"];
```

### Uniqueness

It may happen (rarely, but it's possible) to have not unique strings in the generated array. If you want to avoid it, just change the config.

```php
$config = StringConfig::make()
            ->length(6)
            ->count(3)
            ->unique();

$strings = RandomString::fromConfig($config)->generate();

// Output: ["92ONRj", "Me6oym", "WbBPVc"];
```

### Everything in one line

You can use the `fromArray` method if you don't want to create 2 objects every time.

```php
$string = RandomString::fromArray(['length' => 6, 'charset' => 'ABCD1234'])->generate(); // Output: CCDA1D
```

## Testing

```bash
composer test
```

## Security

While the `RandomString` class is designed to generate random and unpredictable string, it is important to note that it is not a cryptographically secure hash function and should not be used for sensitive applications such as password hashing or cryptographic key generation.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Stefan Damjanovic](https://github.com/stfndamjanovic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
