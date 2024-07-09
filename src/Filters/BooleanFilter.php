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

/**
 * Interpolates a value in the query parameter as true, then executes
 */
class BooleanFilter extends BaseFilter
{
    use IsNegatable;
    use HasClause;
    use HasOperator;

    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        mixed $value = true,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
    ) {
        // Needs to accept value
        parent::__construct($property, $name, $label, $authorize);
        $this->setValue($value);
        $this->setClause($clause);
        $this->setOperator($operator);
        $this->setNegation($negate);
        $this->setType('filter:boolean');
    }

    public static function make(
        array|string|Closure $property,
        string|Closure $name = null,
        mixed $value = true,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'value',
            'label',
            'authorize',
            'clause',
            'operator',
            'negate',
        ));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $this->setActive(request()->boolean($this->getName()));
        $builder->when(
            $this->isActive(),
            fn (Builder|QueryBuilder $builder) => $this->getClause()
                ->apply($builder, 
                    $this->getProperty(), 
                    $this->isNegated() ? $this->getOperator()->negate() : $this->getOperator(), 
                    $this->getValue()
                )
        );
    }
}
