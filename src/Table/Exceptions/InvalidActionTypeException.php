<?php

namespace Jdw5\SurgeVanguard\Table\Exceptions;

use Exception;

class InvalidActionTypeException extends Exception
{
    public static function with(string $type): self
    {
        return new self("Invalid action type [{$type}].");
    }
}
