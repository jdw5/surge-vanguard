<?php

namespace Jdw5\Vanguard\Filters\Enums;

enum Clause: string
{
    case IS = 'is';
    case IS_NOT = 'is_not';
    case STARTS_WITH = 'starts_with';
    case ENDS_WITH = 'ends_with';
    case CONTAINS = 'contains';
    case DOES_NOT_CONTAIN = 'does_not_contain';
    case ALL = 'all';
    case ANY = 'any';
    case JSON = 'json_contains';
    case NOT_JSON = 'json_does_not_contain';
    case JSON_LENGTH = 'json_length';

    public function statement(): string
    {
        return match ($this) {
            self::IS => 'where',
            self::IS_NOT => 'whereNot',
            self::STARTS_WITH => 'where',
            self::ENDS_WITH => 'where',
            self::CONTAINS => 'whereIn',
            self::DOES_NOT_CONTAIN => 'whereNotIn',
            self::ALL => 'all',
            self::ANY => 'any',
            self::JSON => 'whereJsonContains',
            self::NOT_JSON => 'whereJsonDoesntContain',
            self::JSON_LENGTH => 'whereJsonLength',
        };
    }

    public function negate(): Clause
    {
        return match ($this) {
            self::IS => self::IS_NOT,
            self::IS_NOT => self::IS,
            self::STARTS_WITH => self::ENDS_WITH,
            self::ENDS_WITH => self::STARTS_WITH,
            self::CONTAINS => self::DOES_NOT_CONTAIN,
            self::DOES_NOT_CONTAIN => self::CONTAINS,
            self::ALL => self::ANY,
            self::ANY => self::ALL,
            self::JSON => self::NOT_JSON,
            self::NOT_JSON => self::JSON,
            self::JSON_LENGTH => self::JSON_LENGTH,
        };
    }

    public function isMultiple(): bool
    {
        return match ($this) {
            self::CONTAINS, self::DOES_NOT_CONTAIN, self::ALL, self::ANY, self::JSON, self::NOT_JSON => true,
            default => false,
        };
    }

    public function overrideOperator(Operator $operator): Operator
    {
        return match ($this) {
            self::STARTS_WITH, self::ENDS_WITH, self::CONTAINS, self::DOES_NOT_CONTAIN => Operator::LIKE,
            default => $operator,
        };
    }
}