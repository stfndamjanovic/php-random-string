<?php

namespace Stfn\RandomString\Tests;

use PHPUnit\Framework\TestCase;
use Stfn\RandomString\InvalidStringConfigException;
use Stfn\RandomString\RandomString;
use Stfn\RandomString\StringConfig;

class RandomStringTest extends TestCase
{
    public function test_if_it_can_generate_string_without_configuration()
    {
        $string = RandomString::new()->generate();

        $this->assertEquals(16, strlen($string));
    }

    public function test_if_it_can_generate_string_with_specific_length()
    {
        $config = new StringConfig();
        $config->length(10);

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertEquals(10, strlen($string));

        $config->length(12);
        $string = $instance->generate();

        $this->assertEquals(12, strlen($string));
    }

    public function test_if_it_can_generate_string_with_specific_prefix()
    {
        $config = new StringConfig();
        $config->prefix('PREFIX');

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertStringStartsWith('PREFIX', $string);

        $config->prefix('NEW_PREFIX');
        $string = $instance->generate();

        $this->assertStringStartsWith('NEW_PREFIX', $string);
    }

    public function test_if_it_can_generate_string_with_specific_suffix()
    {
        $config = new StringConfig();
        $config->suffix('SUFFIX');

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertStringEndsWith('SUFFIX', $string);

        $config->suffix('NEW_SUFFIX');
        $string = $instance->generate();

        $this->assertStringEndsWith('NEW_SUFFIX', $string);
    }

    public function test_if_it_can_generate_string_with_specific_count()
    {
        $config = new StringConfig();
        $config->count(10);

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertCount(10, $string);
    }

    public function test_if_it_can_generate_only_numbers()
    {
        $config = new StringConfig();
        $config->length(6)
            ->numbersOnly();

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^\d{6}$/', $string);

        $config->length(8);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^\d{8}$/', $string);
    }

    public function test_if_it_can_generate_uppercase_letters()
    {
        $config = new StringConfig();
        $config->length(6)
            ->upperCaseOnly();

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^[A-Z]{6}$/', $string);

        $config->length(8);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^[A-Z]{8}$/', $string);
    }

    public function test_if_it_can_generate_lowercase_letters()
    {
        $config = new StringConfig();
        $config->length(6)
            ->lowerCaseOnly();

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^[a-z]{6}$/', $string);

        $config->length(8);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^[a-z]{8}$/', $string);
    }

    public function test_if_it_can_generate_alphanumeric()
    {
        $config = new StringConfig();
        $config->length(10)
            ->charset(StringConfig::CHARSET_UPPERCASE.StringConfig::CHARSET_LOWERCASE.StringConfig::CHARSET_NUMERIC);

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]{10}$/', $string);

        $config->length(12);
        $string = $instance->generate();

        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]{12}$/', $string);
    }

    public function test_if_it_can_use_skip_callback()
    {
        $config = new StringConfig();
        $config->length(2)
            ->charset('01')
            ->skip(function ($string) {
                return in_array($string, ['10', '11', '01']);
            });

        $instance = new RandomString($config);
        $string = $instance->generate();

        $this->assertEquals('00', $string);
    }

    public function test_if_it_can_generate_not_unique_strings()
    {
        $config = new StringConfig();
        $config->length(2)
            ->count(5)
            ->notUnique()
            ->charset('01')
            ->skip(function ($string) {
                return in_array($string, ['10', '11', '01']);
            });

        $instance = new RandomString($config);
        $strings = $instance->generate();

        foreach ($strings as $string) {
            $this->assertEquals('00', $string);
        }
    }

    public function test_if_it_can_generate_unique_strings()
    {
        $config = new StringConfig();
        $config->length(2)
            ->count(2)
            ->unique()
            ->charset('01')
            ->skip(function ($string) {
                return in_array($string, ['10', '11']);
            });

        $instance = new RandomString($config);
        $strings = $instance->generate();

        $this->assertEqualsCanonicalizing(['01', '00'], $strings);
    }

    public function test_if_it_fails_after_max_possible_combination_reached()
    {
        $config = new StringConfig();
        $config->length(2)
            ->charset('01')
            ->skip(function ($string) {
                return in_array($string, ['10', '11', '01', '00']);
            });

        $instance = new RandomString($config);

        $this->expectException(InvalidStringConfigException::class);
        $instance->generate();
    }

    public function test_if_it_fails_when_count_is_greater_than_possible_combinations()
    {
        $config = new StringConfig();
        $config->length(2)
            ->count(5)
            ->unique()
            ->charset('01');

        $instance = new RandomString($config);

        $this->expectException(InvalidStringConfigException::class);
        $instance->generate();
    }

    public function test_if_it_fails_after_max_possible_combination_reached_using_unique()
    {
        $config = new StringConfig();
        $config->length(2)
            ->charset('01')
            ->count(3)
            ->unique()
            ->skip(function ($string) {
                return in_array($string, ['10', '11']);
            });

        $instance = new RandomString($config);

        $this->expectException(InvalidStringConfigException::class);
        $instance->generate();
    }

    public function test_if_it_can_generate_strings_from_array_config()
    {
        $prefix = 'TEST_';

        $strings = RandomString::fromArray([
            'length' => 12,
            'prefix' => $prefix,
            'count' => 10,
        ])->generate();

        $this->assertStringStartsWith($prefix, $strings[0]);
        $this->assertEquals(12 + strlen($prefix), strlen($strings[0]));
        $this->assertCount(10, $strings);
    }

    public function test_if_it_can_generate_string_from_config_object()
    {
        $config = StringConfig::make(8);

        $string = RandomString::fromConfig($config)->generate();

        $this->assertEquals(8, strlen($string));
    }

    public function test_if_it_can_generate_a_lot_of_not_unique_strings()
    {
        $config = new StringConfig();

        $config->length(10)
            ->count(1000000);

        $strings = RandomString::fromConfig($config)->generate();

        $this->assertCount(1000000, $strings);
    }

    public function test_if_it_can_change_config_on_fly()
    {
        $config = StringConfig::make(12);
        $instance = new RandomString($config);

        $newConfig = StringConfig::make(14);
        $instance->useConfig($newConfig);

        $this->assertNotEquals($config, $instance->getConfig());
    }

    public function test_if_negative_number_cannot_be_used_for_count()
    {
        $config = new StringConfig();
        $config->count(-1);

        $this->expectException(InvalidStringConfigException::class);
        RandomString::fromConfig($config)->generate();
    }

    public function test_if_negative_number_cannot_be_used_for_length()
    {
        $config = new StringConfig();
        $config->length(-1);

        $this->expectException(InvalidStringConfigException::class);
        RandomString::fromConfig($config)->generate();
    }

    public function test_if_charset_cannot_be_empty_string()
    {
        $config = new StringConfig();
        $config->charset('');

        $this->expectException(InvalidStringConfigException::class);
        RandomString::fromConfig($config)->generate();
    }
}
