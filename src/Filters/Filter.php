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
        $this->setClause(Clause::IS);
        $this->setOperator(Operator::EQUAL);
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
