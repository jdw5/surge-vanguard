<?php

namespace Conquest\Table\Filters\Enums;

enum Operator: string
{
    case Equal = '=';
    case NotEqual = '!=';
    case GreaterThan = '>';
    case GreaterThanOrEqual = '>=';
    case LessThan = '<';
    case LessThanOrEqual = '<=';
    case Like = 'like';

    public function invalid(mixed $value)
    {
        return is_null($value) && ! in_array($this, [self::Equal, self::NotEqual]);
    }

    public function label(): string
    {
        return match ($this) {
            self::Equal => 'Equal to',
            self::NotEqual => 'Not equal to',
            self::GreaterThan => 'Greater than',
            self::GreaterThanOrEqual => 'Greater than or equal to',
            self::LessThan => 'Less than',
            self::LessThanOrEqual => 'Less than or equal to',
            self::Like => 'Contains',
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
