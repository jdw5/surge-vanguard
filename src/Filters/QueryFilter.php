<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\CanValidate;
use Conquest\Table\Filters\Concerns\HasQuery;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Allows for custom query filters to be created,
 * based on user-defined value behaviour.
 */
class QueryFilter extends BaseFilter
{
    use HasQuery;
    use CanValidate;
    use CanTransform;

    public function setUp(): void
    {
        $this->setType('filter:query');
    }

    public function __construct(string|Closure $name, string|Closure|null $label = null, Closure $query = null) 
    {
        parent::__construct($name, $label);
        $this->setQuery($query);
    }

    public static function make(string|Closure $name, string|Closure|null $label = null, Closure $query = null): static 
    {
        return resolve(static::class, compact(
            'name',
            'label',
            'query',
        ));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->transformUsing($this->getValueFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value));
        $builder->when(
            $this->isActive() && $this->validateUsing($value),
            fn (Builder|QueryBuilder $builder) => $this->handle($builder),
        );
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $this->getQuery()($builder, $this->getValue());
    }
}
