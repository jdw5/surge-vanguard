<?php

namespace Jdw5\Vanguard\Refining\Filters\Exceptions;

class InvalidDateOperator extends \Exception
{
    public static function invalid(string $operator, string $name): self
    {
        return new self("Invalid date operator [{$operator}] provided for [{$name}] filter.");
    }
}