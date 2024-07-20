<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Table\Filters\Concerns\HasClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Filter extends PropertyFilter
{
    use HasClause;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('filter');
    }

    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        array $metadata = null,
    ) {
        parent::__construct(
            property: $property, 
            name: $name, 
            label: $label, 
            authorize: $authorize, 
            validator: $validator, 
            transform: $transform, 
            metadata: $metadata
        );
        $this->setClause($clause);
        $this->setOperator($operator);
    }

    public static function make(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        array $metadata = null,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'label',
            'authorize',
            'validator',
            'transform',
            'clause',
            'operator',
            'metadata',
        ));
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $this->getClause()
            ->apply($builder,
                $this->getProperty(),
                $this->getOperator(),
                $this->getValue()
            );
    }
}
