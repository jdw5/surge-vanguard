<?php

namespace Jdw5\Vanguard\Refining\Filters;

class InvalidQueryException extends \Exception
{
    public static function invalid(): self
    {
        return new self('The query closure is invalid.');
    }
}