<?php

namespace Jdw5\Vanguard\Filters\Exceptions;

class InvalidOperator extends \Exception
{
    public function __construct(string $operator)
    {
        parent::__construct("Invalid operator [{$operator}] provided for the filter.");
    }
}