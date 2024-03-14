<?php

namespace Jdw5\Vanguard\Refining\Filters;

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
        $this->type('query');
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value($request->query($this->getName()));

        $this->apply($builder, $this->getName(), $this->getValue());

        return;        
    }

    public function apply(Builder $builder, string $property, mixed $value): void
    {
        $builder->when(! is_null($value), function ($builder) use ($value) {
            $this->getQuery()($builder, $value);
        });
    }
}