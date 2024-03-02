<?php

namespace Jdw5\SurgeVanguard\Table\Exceptions;

use Exception;

class InvalidActionException extends Exception
{
    public static function with(string $action, string $table): self
    {
        return new self("Action [{$action}] does not exist in table [{$table}]");
    }
}
