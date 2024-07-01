<?php

namespace Conquest\Table\Filters\Enums;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

enum Clause: string
{
    /** Any and all allow for columns as array */
    case IS = 'is';
    case IS_NOT = 'is_not';
    case SEARCH = 'search';
    case STARTS_WITH = 'starts_with';
    case ENDS_WITH = 'ends_with';
    case CONTAINS = 'contains';
    case DOES_NOT_CONTAIN = 'does_not_contain';
    case ALL = 'all';
    case ANY = 'any';
    case JSON = 'json_contains';
    case NOT_JSON = 'json_does_not_contain';
    case JSON_LENGTH = 'json_length';
    case JSON_KEY = 'json_key';
    case JSON_NOT_KEY = 'json_not_key';
    case JSON_OVERLAPS = 'json_overlaps';
    case JSON_DOESNT_OVERLAP = 'json_doesnt_overlap';
    case FULL_TEXT = 'fulltext';
    case LIKE = 'like';

    public function statement(): string
    {
        return match ($this) {
            self::IS => 'where',
            self::IS_NOT => 'whereNot',
            self::SEARCH => 'search',
            self::STARTS_WITH => 'where',
            self::ENDS_WITH => 'where',
            self::CONTAINS => 'whereIn',
            self::DOES_NOT_CONTAIN => 'whereNotIn',
            self::ALL => 'whereAll',
            self::ANY => 'whereAny',
            self::JSON => 'whereJsonContains',
            self::NOT_JSON => 'whereJsonDoesntContain',
            self::JSON_LENGTH => 'whereJsonLength',
            self::JSON_KEY => 'whereJsonContainsKey',
            self::JSON_NOT_KEY => 'whereJsonDoesntContainKey',
            self::JSON_OVERLAPS => 'whereJsonOverlaps',
            self::JSON_DOESNT_OVERLAP => 'whereJsonDoesntOverlap',
            self::FULL_TEXT => 'whereFullText',
            self::LIKE => 'where',
        };
    }

    public function needsOperator(): bool
    {
        return match ($this) {
            self::JSON_LENGTH, 
            self::JSON_KEY, 
            self::JSON_NOT_KEY, 
            self::JSON_OVERLAPS, 
            self::JSON_DOESNT_OVERLAP, 
            self::FULL_TEXT,
            self::CONTAINS,
            self::DOES_NOT_CONTAIN => false,
            default => true,
        };
    }

    public function isMultiple(): bool
    {
        return match ($this) {
            self::CONTAINS, self::DOES_NOT_CONTAIN, self::JSON, self::NOT_JSON => true,
            default => false,
        };
    }

    public function overrideOperator(Operator $operator): Operator
    {
        return match ($this) {
            self::STARTS_WITH, self::ENDS_WITH, self::SEARCH, self::LIKE => Operator::LIKE,
            default => $operator,
        };
    }

    public function formatValue(mixed $value): mixed
    {
        return match ($this) {
            self::STARTS_WITH => "$value%",
            self::ENDS_WITH => "%$value",
            self::SEARCH, self::LIKE => '%' . strtolower($value) . '%',
            $this->isMultiple() => is_array($value) ? $value : [$value],
            default => $value,
        };
    }

    public function formatProperty(string $property)
    {
        return match ($this) {
            self::ALL, self::ANY => is_array($property) ? $property : [$property],
            self::SEARCH, self::LIKE => DB::raw("lower($property)"),
            default => $property,
        };
    }

    public function apply(Builder|QueryBuilder $builder, string $property, Operator $operator, mixed $value): void
    {
        
        $operator = $this->overrideOperator($operator);

        if ($operator->invalid($value)) return;

        if ($this->needsOperator()) {
            $builder->{$this->statement()} (
                $this->formatProperty($property), 
                $operator->value(), 
                $this->formatValue($value)
            );
            return;
        }

        $builder->{$this->statement()}(
            $this->formatProperty($property), 
            $this->formatValue($value)
        );
    }
}