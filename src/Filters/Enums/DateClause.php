<?php

namespace Conquest\Table\Filters\Enums;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

enum DateClause: string
{
    case DATE = 'date';
    case DAY = 'day';
    case MONTH = 'month';
    case YEAR = 'year';
    case TIME = 'time';

    public function statement(): string
    {
        return match ($this) {
            self::DATE => 'whereDate',
            self::DAY => 'whereDay',
            self::MONTH => 'whereMonth',
            self::YEAR => 'whereYear',
            self::TIME => 'whereTime',
        };
    }

    public function formatValue(Carbon $value): string
    {
        return match ($this) {
            self::DATE => $value->toDateString(),
            self::DAY => $value->day,
            self::MONTH => $value->month,
            self::YEAR => $value->year,
            self::TIME => $value->toTimeString(),
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
