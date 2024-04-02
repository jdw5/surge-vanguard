<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Closure;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Concerns\HasEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasMode;
use Jdw5\Vanguard\Refining\Filters\Enums\FilterMode;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasOperator;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasQueryBoolean;

class Filter extends BaseFilter
{
    use HasMode;
    use HasOperator;
    use HasQueryBoolean;

    protected function setUp(): void
    {
        $this->type('filter');
    }

    public function apply(Builder|QueryBuilder $builder, string $property, mixed $value): void
    {
        if ($this->getMode() === FilterMode::EXACT) {
            $queryMethod = ($this->getQueryBoolean() === 'or') ? 'orWhere' : 'where';
            $builder->{$queryMethod}($property, $this->getOperator(), $value);
            return;
        }

        $operator = match (strtolower($operator = $this->getOperator())) {
            '=', 'like' => 'LIKE',
            'not like' => 'NOT LIKE',
            default => throw new \InvalidArgumentException("Invalid operator [{$operator}] provided for [{$property}] filter.")
        };

        $sql = match ($this->getMode()) {
            FilterMode::LOOSE => "LOWER({$property}) {$operator} ?",
            FilterMode::BEGINS_WITH => "{$property} {$operator} ?",
            FilterMode::ENDS_WITH => "{$property} {$operator} ?",
        };

        $bindings = match ($this->getMode()) {
            FilterMode::LOOSE => ['%' . mb_strtolower($value, 'UTF8') . '%'],
            FilterMode::BEGINS_WITH => ["{$value}%"],
            FilterMode::ENDS_WITH => ["%{$value}"],
        };

        $builder->whereRaw(
            sql: $sql,
            bindings: $bindings,
            boolean: $this->getQueryBoolean()
        );
    }
}