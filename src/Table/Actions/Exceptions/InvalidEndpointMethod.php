<?php

namespace Jdw5\Vanguard\Table\Actions\Exceptions;

class InvalidEndpointMethod extends \Exception
{
    public static function make(string $method): self
    {
        return new static("The method {$method} is not a valid endpoint method");
    }
}