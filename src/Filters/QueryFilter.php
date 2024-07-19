<?php

namespace Conquest\Table\Filters;

use Closure;
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

    public function setUp(): void
    {
        $this->setType('filter:query');
    }

    public function __construct(
        string|Closure $name,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        ?Closure $query = null,
        array $metadata = null,
    ) {
        parent::__construct($name, $label, $authorize, $validator, $transform, $metadata);
        $this->setQuery($query);
    }

    public static function make(
        string|Closure $name,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        ?Closure $query = null,
        array $metadata = null,
    ): static {
        return resolve(static::class, compact(
            'name',
            'label',
            'authorize',
            'validator',
            'transform',
            'query',
            'metadata'
        ));
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $this->getQuery()($builder, $this->getValue());
    }
}
