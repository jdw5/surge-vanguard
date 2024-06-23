<?php

namespace Jdw5\Vanguard\Refining\Filters\Exceptions;

class InvalidMode extends \Exception
{
    public static function make(string $mode): self
    {
        return new self("Invalid mode [{$mode}] provided for the refiner.");
    }
}