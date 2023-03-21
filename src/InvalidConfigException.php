<?php

namespace Stfn\RandomHash;

use Exception;

class InvalidConfigException extends Exception
{
    public static function maxCombinationReached()
    {
        return new self('Cannot generate hash because there is no more possible combinations. Check your config.');
    }

    public static function invalidCharset()
    {
        return new self('Invalid charset.');
    }

    public static function propertyNotPositiveNumber($property)
    {
        return new self(ucfirst($property).' must be positive number.');
    }
}
