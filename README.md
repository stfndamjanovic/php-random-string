# Random string generator for PHP

[![Tests](https://img.shields.io/github/actions/workflow/status/stfndamjanovic/php-random-string/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/stfndamjanovic/php-random-string/actions/workflows/run-tests.yml)

Description

## Installation

You can install the package via composer:

```bash
Not published yet
```

## Usage

Simple example without any configuration.

```php
$string = RandomString::new()->generate();

echo $string; // Output: RIKdjFzuDaN12RiJ
```

If you want to generate string consist of numbers only, you can do it like this:
```php
$config = StringConfig::make()
            ->length(6)
            ->numbersOnly();

$string = RandomString::fromConfig($config)->generate();

echo $string; // Output: 649432
```

Or you can use your custom charset for generating random string:
```php
$config = StringConfig::make()
            ->charset("ABCD1234");

$string = RandomString::fromConfig($config)->generate();

echo $string; // Output: 3B41B32C2A12A3A1
```

You can use shorthand for config.
```php
$string = RandomString::fromArray([
    'length' => 6,
    'charset' => 'ABCD1234'
])->generate();

echo $string; // Output: 3B41B32C2A12A3A1
```

If you want to generate more than one string, with more than one configuration option, you can do it like this:
```php
use Stfn\RandomString\StringConfig;

$config = StringConfig::make()
    ->charset("ABCD1234")
    ->length(5)
    ->prefix("PREFIX_")
    ->suffix("_SUFFIX")
    ->count(10)
    ->unique()
    ->skip(function ($string) {
        return in_array($string, ["PREFIX_BCD1234A_SUFFIX"]);
    });

$strings = RandomString::fromConfig($config)->generate();

echo $string; // Output: [
    "PREFIX_CAC23_SUFFIX"
    "PREFIX_3AAD2_SUFFIX"
    "PREFIX_CC21D_SUFFIX"
    "PREFIX_121C3_SUFFIX"
    "PREFIX_43ABC_SUFFIX"
    "PREFIX_D432A_SUFFIX"
    "PREFIX_43BC3_SUFFIX"
    "PREFIX_11BBB_SUFFIX"
    "PREFIX_31121_SUFFIX"
    "PREFIX_3AB1B_SUFFIX"
];
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
