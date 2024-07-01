<?php

namespace Conquest\Table\Filters\Exceptions;

use Exception;

class QueryNotDefined extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("The query for filter [{$name}] has not been provided.");
    }
}
