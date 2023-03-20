<?php

declare(strict_types=1);

namespace Stfn\RandomHash;

class RandomHash
{
    protected HashConfig $config;

    protected array $hashes = [];

    private array $skipped = [];

    public function __construct(HashConfig $hashConfig)
    {
        $this->config = $hashConfig;
    }

    public static function make(HashConfig $hashConfig)
    {
        return new self($hashConfig);
    }

    public static function fromConfig($array)
    {
        return new self(HashConfig::fromArray($array));
    }

    public function generate()
    {
        $this->config->validate();

        $this->reset();

        $charset = $this->config->getCharset();
        $length = $this->config->getLength();

        $maxCombinations = pow(strlen($this->config->getCharset()), $this->config->getLength());

        for ($i = 0; $i < $this->config->getCount(); $i++) {
            if ($maxCombinations <= count($this->skipped)) {
                throw InvalidConfigException::maxCombinationReached();
            }

            $hash = '';

            for ($j = 0; $j < $length; $j++) {
                $hash .= $charset[rand(0, strlen($charset) - 1)];
            }

            $hash = $this->config->getPrefix() . $hash . $this->config->getSuffix();

            if ($this->shouldSkip($hash)) {
                $i--;
                $this->skipHash($hash);
                continue;
            }

            $this->addHash($hash);
        }

        if (count($this->hashes) == 0) {
            return null;
        }

        if (count($this->hashes) == 1) {
            return reset($this->hashes);
        }

        return $this->hashes;
    }

    public function useConfig(HashConfig $hashConfig)
    {
        $this->config = $hashConfig;
    }

    protected function addHash(string $hash)
    {
        $this->hashes[] = $hash;
    }

    protected function getHashes()
    {
        return $this->hashes;
    }

    protected function shouldSkip($hash)
    {
        $shouldSkip = false;

        if ($this->config->isUnique()) {
            $shouldSkip = in_array($hash, $this->getHashes());
        }

        if ($this->config->hasSkipCallback()) {
            $shouldSkip = $shouldSkip || $this->config->getSkipCallback()($hash);
        }

        return $shouldSkip;
    }

    protected function reset()
    {
        $this->hashes = [];
        $this->skipped = [];
    }

    protected function skipHash($hash)
    {
        if (!in_array($hash, $this->skipped)) {
            $this->skipped[] = $hash;
        }
    }
}
