<?php

namespace Jdw5\SurgeVanguard\Table\Exceptions;

use Exception;

class CouldNotResolveTableException extends Exception
{
    public static function with(string $table): self
    {
        return new self("Table [{$table}] could not be resolved from the container.");
    }
}
