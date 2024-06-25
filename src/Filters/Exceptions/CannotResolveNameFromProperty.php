<?php

namespace Jdw5\Vanguard\Filters\Exceptions;

use Exception;

class CannotResolveNameFromProperty extends Exception
{
    public function __construct(array $properties)
    {
        parent::__construct("Filter name cannot be inferred from array of properties: " . json_encode($properties) . ". The name must be explicitly provided.");
    }
}