<?php

namespace Jdw5\SurgeVanguard\Table\Actions\Exceptions;

class InvalidEndpointMethod extends \Exception
{
    public function __construct(string $method)
    {
        parent::__construct("Invalid endpoint method: {$method}");
    }
}