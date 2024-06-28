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
use Override;

/**
 * Interpolates a value in the query parameter as true, then executes
 */
class BooleanFilter extends BaseFilter
{
    use IsNegatable;
    use HasClause;
    use HasOperator;

    #[Override]
    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setClause($clause);
        $this->setOperator($operator);
        $this->setNegation($negate);
        $this->setType('filter:boolean');
    }

    public static function make(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
    ): static {
        return new static($property, $name, $label, $authorize, $clause, $operator, $negate);
    }

    #[Override]
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