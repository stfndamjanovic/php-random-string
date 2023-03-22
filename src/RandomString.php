<?php

declare(strict_types=1);

namespace Stfn\RandomString;

class RandomString
{
    protected StringConfig $config;

    protected array $strings = [];

    protected array $skipped = [];

    public function __construct(StringConfig $config)
    {
        $this->config = $config;
    }

    public static function new(): self
    {
        return new self(new StringConfig());
    }

    public static function fromConfig(StringConfig $config): self
    {
        return new self($config);
    }

    public static function fromArray(array $input): self
    {
        return new self(StringConfig::fromArray($input));
    }

    public function generate(): string|array
    {
        $this->config->validate();

        $this->reset();

        $charset = $this->config->getCharset();
        $length = $this->config->getLength();

        $charsetLength = strlen($this->config->getCharset());
        $maxCombinations = pow($charsetLength, $this->config->getLength());

        for ($i = 0; $i < $this->config->getCount(); $i++) {
            if ($maxCombinations <= count($this->skipped)) {
                throw InvalidStringConfigException::maxCombinationReached();
            }

            $randomBytes = random_bytes($length);

            for ($j = $length - 1; $j > 0; $j--) {
                $randomInt = random_int(0, $j);
                [$randomBytes[$j], $randomBytes[$randomInt]] = [$randomBytes[$randomInt], $randomBytes[$j]];
            }

            $string = '';

            for ($j = 0; $j < $length; $j++) {
                $string .= $charset[ord($randomBytes[$j]) % $charsetLength];
            }

            $string = $this->config->getPrefix().$string.$this->config->getSuffix();

            if ($this->shouldSkip($string)) {
                $i--;
                $this->skipString($string);

                continue;
            }

            $this->addString($string);
        }

        if (count($this->strings) == 1) {
            return reset($this->strings);
        }

        return $this->strings;
    }

    public function useConfig(StringConfig $config): void
    {
        $this->config = $config;
    }

    public function getConfig(): StringConfig
    {
        return $this->config;
    }

    protected function addString(string $string): void
    {
        $this->strings[] = $string;
    }

    protected function getStrings(): array
    {
        return $this->strings;
    }

    protected function shouldSkip(string $string): bool
    {
        $shouldSkip = false;

        if ($this->config->isUnique()) {
            $shouldSkip = in_array($string, $this->getStrings());
        }

        if ($this->config->hasSkipCallback()) {
            $shouldSkip = $shouldSkip || $this->config->getSkipCallback()($string);
        }

        return $shouldSkip;
    }

    protected function reset(): void
    {
        $this->strings = [];
        $this->skipped = [];
    }

    protected function skipString(string $string): void
    {
        if (! in_array($string, $this->skipped)) {
            $this->skipped[] = $string;
        }
    }
}
