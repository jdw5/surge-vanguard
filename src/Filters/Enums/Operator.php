<?php

namespace Conquest\Table\Filters\Enums;

enum Operator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_OR_EQUAL = '<=';
    case LIKE = 'like';

    public function invalid(mixed $value)
    {
        return is_null($value) && ! in_array($this, [self::EQUAL, self::NOT_EQUAL]);
    }

    public function negate(): Operator
    {
        return match ($this) {
            self::EQUAL => self::NOT_EQUAL,
            self::NOT_EQUAL => self::EQUAL,
            self::GREATER_THAN => self::LESS_THAN_OR_EQUAL,
            self::GREATER_THAN_OR_EQUAL => self::LESS_THAN,
            self::LESS_THAN => self::GREATER_THAN_OR_EQUAL,
            self::LESS_THAN_OR_EQUAL => self::GREATER_THAN,
            self::LIKE => self::LIKE,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::EQUAL => 'Equal to',
            self::NOT_EQUAL => 'Not equal to',
            self::GREATER_THAN => 'Greater than',
            self::GREATER_THAN_OR_EQUAL => 'Greater than or equal to',
            self::LESS_THAN => 'Less than',
            self::LESS_THAN_OR_EQUAL => 'Less than or equal to',
            self::LIKE => 'Contains',
        };
    }



    public function value(): string
    {
        return $this->value;
    }
}
