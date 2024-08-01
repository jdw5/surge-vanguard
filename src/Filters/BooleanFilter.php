<?php

namespace Conquest\Table\Filters;

use Conquest\Table\Filters\Concerns\HasClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Request;

/**
 * Interpolates a value in the query parameter as true, then executes
 */
class BooleanFilter extends PropertyFilter
{
    use HasClause;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('filter:boolean');
        $this->setClause(Clause::IS);
        $this->setOperator(Operator::EQUAL);
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

    public function toArray(): array
    {
        $array = parent::toArray();
        unset($array['value']);

        return $array;
    }
}
