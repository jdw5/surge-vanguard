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

class OperatorFilter extends BaseFilter
{
    use HasClause;
    use HasOperators;

    protected ?Operator $operator = null;

    public function setUp(): void
    {
        $this->setType('filter:operator');
    }

    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|Clause $clause = Clause::IS,
        array $operators = null,
        array $metadata = null,
    ) {
        parent::__construct($property, $name, $label, $authorize, $validator, $transform, $metadata);
        $this->setClause($clause);
        $this->setOperators($operators);
    }

    public static function make(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|Clause $clause = Clause::IS,
        array $operators = null,
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
            'operators',
            'metadata',
        ));
    }

    public function getOperator(): Operator
    {
        return $this->operator;
    }

    public function setOperator(?Operator $operator): void
    {
        if (is_null($operator)) return;
        $this->operator = $operator;
    }

    public function getOperatorFromRequest(): ?Operator
    {
        $q = Request::input('['.$this->getName().']', null);

        try {
            return Operator::from($q);
        } catch (Exception $e) {
            return null;
        }
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->transformUsing($this->getValueFromRequest());
        $this->setOperator($this->getOperatorFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value) && !is_null($this->operator));

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

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'operators' => $this->getOperators($this->getOperator()?->value),
        ]);
    }
}
