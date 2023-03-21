<?php

declare(strict_types=1);

namespace Stfn\RandomHash;

class HashConfig
{
    const CHARSET_UPPERCASE = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const CHARSET_LOWERCASE = "abcdefghijklmnopqrstuvwxyz";
    const CHARSET_NUMERIC = "0123456789";

    protected $length;

    protected $count = 1;

    protected $charset;

    protected $prefix = '';

    protected $suffix = '';

    protected $unique = false;

    protected $skipCallback;

    public function __construct($length = 16)
    {
        $this->length = $length;
        $this->charset = self::CHARSET_LOWERCASE . self::CHARSET_UPPERCASE . self::CHARSET_NUMERIC;
    }

    public static function make($length = 16)
    {
        return new self($length);
    }

    public static function fromArray(array $array)
    {
        $object = new self();

        $allowedProperties = ['count', 'prefix', 'suffix', 'length', 'charset', 'unique'];

        foreach ($array as $property => $value) {
            if (property_exists($object, $property) && in_array($property, $allowedProperties)) {
                $object->{$property} = $value;
            }
        }

        return $object;
    }

    public function validate()
    {
        if (!is_string($this->charset) || empty($this->charset)) {
            throw InvalidConfigException::invalidCharset();
        }

        if ($this->length < 1) {
            throw InvalidConfigException::propertyNotPositiveNumber('length');
        }

        if ($this->count < 1) {
            throw InvalidConfigException::propertyNotPositiveNumber('count');
        }
    }

    public function charset(string $charset): self
    {
        $this->charset = $charset;

        return $this;
    }

    public function upperCaseOnly(): self
    {
        $this->charset = self::CHARSET_UPPERCASE;

        return $this;
    }

    public function lowerCaseOnly(): self
    {
        $this->charset = self::CHARSET_LOWERCASE;

        return $this;
    }

    public function numbersOnly(): self
    {
        $this->charset = self::CHARSET_NUMERIC;

        return $this;
    }

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function suffix(string $suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function length(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function skip(callable $callback)
    {
        $this->skipCallback = $callback;

        return $this;
    }

    public function unique()
    {
        $this->unique = true;

        return $this;
    }

    public function notUnique()
    {
        $this->unique = false;

        return $this;
    }

    public function hasSkipCallback()
    {
        return is_callable($this->skipCallback);
    }

    public function getSkipCallback()
    {
        return $this->skipCallback;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }

    public function isUnique()
    {
        return $this->unique;
    }

    public function count(int $count)
    {
        $this->count = $count;

        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }
}
