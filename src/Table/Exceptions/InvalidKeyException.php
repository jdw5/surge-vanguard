<?php

namespace Jdw5\Vanguard\Table\Exceptions;

class InvalidKeyException extends \Exception
{
    public static function make(): self
    {
        return new self("The table has an invalid or missing key.");
    }
}