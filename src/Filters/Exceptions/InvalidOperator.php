<?php

namespace Conquest\Table\Filters\Exceptions;

use Exception;

class InvalidOperator extends Exception
{
    public function __construct(string $operator)
    {
        parent::__construct("Invalid operator [{$operator}] provided for the filter.");
    }
}
