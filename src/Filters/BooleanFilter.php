<?php

namespace Jdw5\Vanguard\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Filters\BaseFilter;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Filters\Concerns\HasClause;
use Jdw5\Vanguard\Filters\Concerns\HasOperator;
use Jdw5\Vanguard\Filters\Concerns\IsNegatable;
use Jdw5\Vanguard\Filters\Enums\Clause;
use Jdw5\Vanguard\Filters\Enums\Operator;
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