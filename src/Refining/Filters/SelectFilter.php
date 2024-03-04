<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Database\Eloquent\Builder;

class SelectFilter extends Filter
{

    protected string $operator = '=';

    protected function setUp(): void
    {
        $this->type('select');
    }

    public function not(): static
    {
        $this->operator = '!=';
        return $this;
    }

    public function apply(Builder $builder, mixed $value, string $property): void
    {
        $method = match ($this->operator) {
            '!=' => 'whereNotIn',
            default => 'whereIn',
        };

        $builder->{$method}($property, $this->getOperator(), $value);
        return;
        
    }
}