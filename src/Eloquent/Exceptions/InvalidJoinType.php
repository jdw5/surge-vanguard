<?php

namespace Jdw5\SurgeVanguard\Eloquent\Exceptions;

class InvalidJoinType extends \Exception
{
    public static function invalid(string $type): self
    {
        return new self("Invalid join type {$type} provided.");
    }
}