<?php

namespace Conquest\Table\Exceptions;

use Exception;

class CannotResolveNameFromProperty extends Exception
{
    public function __construct(array $property)
    {
        parent::__construct('Cannot resolve name from property [' . implode(', ', $property) . ']. An explicit name for the property must be provided.');
    }
}
