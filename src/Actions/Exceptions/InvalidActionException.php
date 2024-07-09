<?php

namespace Conquest\Table\Actions\Exceptions;

use Exception;

class InvalidActionException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("The provided action {$name} does not exist for the given table.");
    }
}