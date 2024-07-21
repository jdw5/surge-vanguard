<?php

namespace Conquest\Table\Filters\Exceptions;

use Exception;

class QueryNotDefined extends Exception
{
    public function __construct()
    {
        parent::__construct('Query for filter [{}] has not been provided.');
    }
}
