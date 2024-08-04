<?php

namespace Conquest\Table\Filters\Enums;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

enum DateClause: string
{
    case Date = 'date';
    case Day = 'day';
    case Month = 'month';
    case Year = 'year';
    case Time = 'time';

    public function statement(): string
    {
        return match ($this) {
            self::Date => 'whereDate',
            self::Day => 'whereDay',
            self::Month => 'whereMonth',
            self::Year => 'whereYear',
            self::Time => 'whereTime',
        };
    }

    public function formatValue(Carbon $value): string
    {
        return match ($this) {
            self::Date => $value->toDateString(),
            self::Day => $value->day,
            self::Month => $value->month,
            self::Year => $value->year,
            self::Time => $value->toTimeString(),
        };
    }

    public function apply(Builder|QueryBuilder $builder, string $property, Operator $operator, Carbon $value): void
    {
        $builder->{$this->statement()}(
            $property,
            $operator->value(),
            $this->formatValue($value)
        );
    }
}
