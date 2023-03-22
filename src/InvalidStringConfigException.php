<?php

namespace Stfn\RandomString;

use Exception;

class InvalidStringConfigException extends Exception
{
    public static function maxCombinationReached()
    {
        return new self('Cannot generate string because there is no more possible combinations. Check your config.');
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
