<?php

namespace Jdw5\Vanguard\Refining\Filters\Exceptions;

use Illuminate\Database\Eloquent\Builder;

class InvalidQueryException extends \Exception
{
    public static function missing(): self
    {
        return new self('The query closure is invalid.');
    }

    public static function invalid(): self
    {
        return new self("The first parameter of the query closure must be an instance of " . Builder::class);
    }

    public static function count(int $count): self
    {
        return new self("The query closure must have exactly {$count} parameters.");
    }
}