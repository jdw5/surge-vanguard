<?php

namespace Jdw5\Vanguard\Filters\Enums;

enum Operator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_OR_EQUAL = '<=';
    case LIKE = 'LIKE';

    public function invvalid(mixed $value)
    {
        return is_null($value) && !in_array($this, [self::EQUAL, self::NOT_EQUAL]);    
    }
}