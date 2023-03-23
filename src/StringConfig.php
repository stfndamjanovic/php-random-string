<?php

declare(strict_types=1);

namespace Stfn\RandomString;

use Closure;

class StringConfig
{
    const CHARSET_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    const CHARSET_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';

    const CHARSET_NUMERIC = '0123456789';

    protected int $length;

    protected int $count = 1;

    protected string $charset;

    protected string $prefix = '';

    protected string $suffix = '';

    protected bool $unique = false;

    protected Closure|null $skipCallback = null;

    public function __construct($length = 16)
    {
        $this->length = $length;
        $this->charset = self::CHARSET_LOWERCASE.self::CHARSET_UPPERCASE.self::CHARSET_NUMERIC;
    }

    public static function make($length = 16): self
    {
        return new self($length);
    }

    public static function fromArray(array $array): self
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

    public function validate(): void
    {
        if (empty($this->charset)) {
            throw InvalidStringConfigException::invalidCharset();
        }

        if ($this->length < 1) {
            throw InvalidStringConfigException::propertyNotPositiveNumber('length');
        }

        if ($this->count < 1) {
            throw InvalidStringConfigException::propertyNotPositiveNumber('count');
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

    public function length(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function count(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function skip(callable $callback): self
    {
        $this->skipCallback = $callback;

        return $this;
    }

    public function prefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function suffix(string $suffix): self
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function unique(): self
    {
        $this->unique = true;

        return $this;
    }

    public function notUnique(): self
    {
        $this->unique = false;

        return $this;
    }

    public function hasSkipCallback(): bool
    {
        return is_callable($this->skipCallback);
    }

    public function getSkipCallback(): Closure|null
    {
        return $this->skipCallback;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
