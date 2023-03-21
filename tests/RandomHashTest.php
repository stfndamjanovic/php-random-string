<?php

namespace Stfn\RandomHash\Tests;

use PHPUnit\Framework\TestCase;
use Stfn\RandomHash\InvalidConfigException;
use Stfn\RandomHash\RandomHash;
use Stfn\RandomHash\HashConfig;

class RandomHashTest extends TestCase
{
    public function test_if_it_can_generate_hash_with_specific_length()
    {
        $config = new HashConfig();
        $config->length(10);

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertEquals(10, strlen($hash));

        $config->length(12);
        $hash = $instance->generate();

        $this->assertEquals(12, strlen($hash));
    }

    public function test_if_it_can_generate_hash_with_specific_prefix()
    {
        $config = new HashConfig();
        $config->prefix("PREFIX");

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertStringStartsWith("PREFIX", $hash);

        $config->prefix("NEW_PREFIX");
        $hash = $instance->generate();

        $this->assertStringStartsWith("NEW_PREFIX", $hash);
    }

    public function test_if_it_can_generate_hash_with_specific_suffix()
    {
        $config = new HashConfig();
        $config->suffix("SUFFIX");

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertStringEndsWith("SUFFIX", $hash);

        $config->suffix("NEW_SUFFIX");
        $hash = $instance->generate();

        $this->assertStringEndsWith("NEW_SUFFIX", $hash);
    }

    public function test_if_it_can_generate_hash_with_specific_count()
    {
        $config = new HashConfig();
        $config->count(10);

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertCount(10, $hash);
    }

    public function test_if_it_can_generate_only_numbers()
    {
        $config = new HashConfig();
        $config->length(6)
            ->numbersOnly();

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^\d{6}$/', $hash);

        $config->length(8);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^\d{8}$/', $hash);
    }

    public function test_if_it_can_generate_uppercase_letters()
    {
        $config = new HashConfig();
        $config->length(6)
            ->upperCaseOnly();

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^[A-Z]{6}$/', $hash);

        $config->length(8);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^[A-Z]{8}$/', $hash);
    }

    public function test_if_it_can_generate_lowercase_letters()
    {
        $config = new HashConfig();
        $config->length(6)
            ->lowerCaseOnly();

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^[a-z]{6}$/', $hash);

        $config->length(8);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^[a-z]{8}$/', $hash);
    }

    public function test_if_it_can_generate_alphanumeric()
    {
        $config = new HashConfig();
        $config->length(10)
            ->charset(HashConfig::CHARSET_UPPERCASE . HashConfig::CHARSET_LOWERCASE . HashConfig::CHARSET_NUMERIC);

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]{10}$/', $hash);

        $config->length(12);
        $hash = $instance->generate();

        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]{12}$/', $hash);
    }

    public function test_if_it_can_use_skip_callback()
    {
        $config = new HashConfig();
        $config->length(2)
            ->charset('01')
            ->skip(function ($hash) {
                return in_array($hash, ['10', '11', '01']);
            });

        $instance = new RandomHash($config);
        $hash = $instance->generate();

        $this->assertEquals('00', $hash);
    }

    public function test_if_it_fail_after_max_possible_combination_reached()
    {
        $config = new HashConfig();
        $config->length(2)
            ->charset('01')
            ->skip(function ($hash) {
                return in_array($hash, ['10', '11', '01', '00']);
            });

        $instance = new RandomHash($config);

        $this->expectException(InvalidConfigException::class);
        $instance->generate();
    }

    public function test_if_it_can_generate_hashes_from_array_config()
    {
        $prefix = 'TEST_';

        $hashes = RandomHash::fromConfig([
            'length' => 12,
            'prefix' => $prefix,
            'count' => 10
        ])->generate();

        $this->assertStringStartsWith($prefix, $hashes[0]);
        $this->assertEquals(12 + strlen($prefix), strlen($hashes[0]));
        $this->assertCount(10, $hashes);
    }

    public function test_if_it_can_generate_hash_from_config_object()
    {
        $config = HashConfig::fromArray(['length' => 8]);

        $hash = RandomHash::make($config)->generate();

        $this->assertEquals(8, strlen($hash));
    }

    public function test_if_it_can_generate_a_lot_of_not_unique_hashes()
    {
        $config = new HashConfig();

        $config->length(10)
            ->count(1000000);

        $hashes = RandomHash::make($config)->generate();

        $this->assertCount(1000000, $hashes);
    }

    public function test_if_negative_number_cannot_be_used_for_count()
    {
        $config = new HashConfig();
        $config->count(-1);

        $this->expectException(InvalidConfigException::class);
        RandomHash::make($config)->generate();
    }

    public function test_if_negative_number_cannot_be_used_for_length()
    {
        $config = new HashConfig();
        $config->length(-1);

        $this->expectException(InvalidConfigException::class);
        RandomHash::make($config)->generate();
    }

    public function test_if_charset_cannot_be_empty_string()
    {
        $config = new HashConfig();
        $config->charset('');

        $this->expectException(InvalidConfigException::class);
        RandomHash::make($config)->generate();
    }
}
