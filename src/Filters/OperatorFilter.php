<?php

namespace Conquest\Table\Filters;

use Closure;
use Exception;
use Illuminate\Support\Facades\Request;
use Conquest\Table\Filters\Enums\Clause;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Concerns\HasClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Concerns\HasOperators;
use Illuminate\Database\Query\Builder as QueryBuilder;

class OperatorFilter extends PropertyFilter
{
    use HasClause;
    use HasOperators;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('filter:operator');
        $this->setClause(Clause::IS);
    }

    public function getOperatorFromRequest(): ?Operator
    {
        $q = Request::string('['.$this->getName().']');
        return Operator::tryFrom($q);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->transformUsing($this->getValueFromRequest());
        $this->setOperator($this->getOperatorFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value));

        $builder->when(
            $this->isActive() && $this->validateUsing($value),
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

    public function filtering(mixed $value): bool
    {
        return !is_null($value) && 
            collect($this->getOperators())->some(fn ($operator) => $operator->value === $this->getOperator()?->value);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'operators' => $this->getOperatorOptions($this->getOperator()?->value)->toArray(),
        ]);
    }
}
