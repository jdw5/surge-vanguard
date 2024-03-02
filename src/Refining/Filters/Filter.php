<?php

namespace Jdw5\SurgeVanguard\Refining\Filters;

use Closure;
use Illuminate\Http\Request;
use Jdw5\SurgeVanguard\Refining\Concerns\HasEnum;
use Jdw5\SurgeVanguard\Refining\Filters\Enums\FilterMode;
use Illuminate\Database\Eloquent\Builder;

class Filter extends BaseFilter
{
    use HasEnum;
    
    protected Closure|string $operator = '=';
    protected FilterMode $mode = FilterMode::EXACT;

    protected function setUp(): void
    {
        $this->type('filter');
    }

    public function operator(string|Closure $operator): static
    {
        $this->operator = $operator;
        return $this;
    }

    public function mode(string|\Closure $mode): static
    {
        $this->mode = $mode;
        return $this;
    }

    public function exact(): static
    {
        $this->mode = FilterMode::EXACT;
        return $this;
    }

    public function loose(): static
    {
        $this->mode = FilterMode::LOOSE;
        return $this;
    }

    public function beginsWith(): static
    {
        $this->mode = FilterMode::BEGINS_WITH;
        return $this;
    }

    public function endsWith(): static
    {
        $this->mode = FilterMode::ENDS_WITH;
        return $this;
    }

    public function getOperator(): string
    {
        return $this->evaluate($this->operator);
    }

    public function getMode(): FilterMode
    {
        return $this->evaluate($this->mode);        
    }

    public function apply(Builder $builder, mixed $value, string $property): void
    {
        if (($enumClass = $this->getEnumClass()) && !$value instanceof \BackedEnum) {
            $value = $enumClass::tryFrom($value);

            if (!$value) {
                return;
            }
        }

        if ($this->getMode() === FilterMode::EXACT) {
            $builder->where($property, $this->getOperator(), $value);
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
            // boolean: $isRelation ? 'and' : $this->getQueryBoolean(),
        );
    }
}