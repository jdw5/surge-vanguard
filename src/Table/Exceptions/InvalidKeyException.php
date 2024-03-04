<?php

namespace Jdw5\Vanguard\Table\Exceptions;

class InvalidKeyException extends \Exception
{
    public static function invalid(): static
    {
        return new static("The table has an invalid or missing key.");
    }
}