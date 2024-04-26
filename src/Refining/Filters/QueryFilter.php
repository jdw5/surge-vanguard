<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Refinement;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Contracts\Filters;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasQuery;

class QueryFilter extends BaseFilter
{
    use HasQuery;

    protected function setUp(): void
    {
        $this->setType('query');
    }

    public function refine(Builder|QueryBuilder $builder, ?Request $request = null): void
    {
        if (\is_null($request)) $request = request();
        
        $this->setValue($request->query($this->getName()));

        $this->apply($builder, $this->getName(), $this->getValue());

        return;        
    }

    public function apply(Builder|QueryBuilder $builder, string $property, mixed $value): void
    {
        $builder->when(!\is_null($value), function ($builder) use ($value) {
            $this->getQuery()($builder, $value);
        });
    }
}