<?php

namespace Conquest\Table\Columns\Exceptions;

class ReservedColumnName extends \Exception
{
    public static function make(string $name): self
    {
        return new self("Column name {$name} is reserved and cannot be used.");
    }
}