<?php

namespace Jdw5\Vanguard\Table\Exceptions;

class KeyCannotBePreference extends \Exception
{
    public static function invalid(): self
    {
        return new self('The key column cannot be preferenced.');
    }
}