<?php

namespace Conquest\Table\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Filters\BaseFilter;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Conquest\Table\Filters\Concerns\HasClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Concerns\IsNegatable;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Facades\Request;

/**
 * Interpolates a value in the query parameter as true, then executes
 */
class BooleanFilter extends BaseFilter
{
    use HasClause;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('filter:boolean');
    }

    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        mixed $value = true,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        array $metadata = null,
    ) {
        // Needs to accept value
        parent::__construct($property, $name, $label, $authorize, $metadata);
        $this->setValue($value);
        $this->setClause($clause);
        $this->setOperator($operator);
    }

    public static function make(
        array|string|Closure $property,
        string|Closure $name = null,
        mixed $value = true,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        array $metadata = null,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'value',
            'label',
            'authorize',
            'clause',
            'operator',
            'metadata',
        ));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $this->setActive(Request::boolean($this->getName()));
        $builder->when(
            $this->isActive(),
            fn (Builder|QueryBuilder $builder) => $this->handle($builder),
        );
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
