<?php

namespace Jdw5\SurgeVanguard\Table\Exceptions;

use Jdw5\SurgeVanguard\Table\Table;

class InvalidTableException extends \Exception
{
    public static function with(string $table): self
    {
        return new self(sprintf("Table [{$table}] must extend the [%s] class.", Table::class));
    }
}
