<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Options\Option;
use Jdw5\Vanguard\Refining\Filters\BaseFilter;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasOperator;

class SelectFilter extends BaseFilter
{
    use HasOperator;

    protected function setUp(): void
    {
        $this->setType('select');
    }

    public function updateOptionActivity(mixed $value): void
    {
        $this->setValue(explode(',', $value));

        if ($this->hasOptions()) {
            $this->getOptions()->each(fn (Option $option) => $option->active(\in_array($option->getValue(), $this->getValue())));
        }
    }

    public function apply(Builder|QueryBuilder $builder, string $property, mixed $value): void
    {
        $method = match ($this->getOperator()) {
            '!=' => 'whereNotIn',
            default => 'whereIn',
        };

        $builder->{$method}($property, $value, $this->getQueryBoolean());
        return;        
    }
}