<?php

namespace Jdw5\Vanguard\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Filters\Concerns\HasQuery;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Override;

/**
 * Allows for custom query filters to be created, 
 * based on user-defined value behaviour.
 */
class QueryFilter extends BaseFilter
{
    use HasQuery;

    #[Override]
    public function __construct(
        string|Closure $name,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        Closure $query = null,
        Closure $condition = null,
    ) {
        $this->setName($name);
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        $this->setAuthorize($authorize);
        $this->setQuery($query);
        $this->setValidator($condition);
        $this->setType('filter:custom');

    }
    
    public static function make(
        string|Closure $name,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        Closure $query = null,
        Closure $condition = null,
    ): static {
        return new static($name, $label, $authorize, $query, $condition);
    }

    #[Override]
    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request(); 
        $this->setValue($request->query($this->getName()));
        $this->setActive($this->filtering($request));

        $builder->when(
            value: $this->isActive() && $this->validateUsing($this->getValue()),
            callback: fn (Builder|QueryBuilder $builder) => ($this->getQuery())($builder, $this->getValue())
        );        
    }
}