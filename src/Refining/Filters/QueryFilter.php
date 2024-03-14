<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasQuery;

class QueryFilter extends BaseFilter
{
    use HasQuery;

    public static function make(string $name, ?\Closure $query): static
    {
        return resolve(static::class, compact('name', 'query'));
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value($request->query($this->getName()));

        $builder->when(! is_null($this->getValue()), function ($builder) {
            $this->getQuery()($builder, $this->getValue());
        });

        return;


        
    }
}