<?php

namespace Jdw5\Vanguard\Table\Columns\Exceptions;

class InvalidSortDirection extends \Exception
{
    public static function with(string $direction): self
    {
        return new self("Invalid sort direction [{$direction}].");
    }    
}