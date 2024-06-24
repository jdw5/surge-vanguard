<?php

namespace Jdw5\Vanguard\Columns\Exceptions;

class ReservedColumnName extends \Exception
{
    public static function make(string $name): self
    {
        return new self("Column name {$name} is reserved and cannot be used.");
    }
}