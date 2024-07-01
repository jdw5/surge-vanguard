<?php

namespace Conquest\Table\Filters\Exceptions;

use Exception;

class InvalidClause extends Exception
{
    public function __construct(string $mode)
    {
        parent::__construct("Invalid clause [{$mode}] provided for the filter.");
    }
}
