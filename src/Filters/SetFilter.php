<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Options\Concerns\HasOptions;
use Conquest\Table\Filters\Concerns\IsMultiple;
use Conquest\Table\Filters\Concerns\IsRestrictable;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class SetFilter extends BaseFilter
{
    use IsMultiple;
    use HasOptions;
    use IsRestrictable;

    public function __construct(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
        bool $multiple = false,
        array $options = [],
        bool|Closure|null $restrict = null,
    ) {
        parent::__construct($property, $name, $label, $authorize, $clause, $operator, $negate);
        if ($multiple) {
            $this->multiple();
        }
        $this->setOptions($options);
        $this->setRestricted($restrict);
        $this->setType('filter:select');

    }

    public static function make(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
        bool $multiple = false,
        array $options = [],
        bool|Closure|null $restrict = null,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'label',
            'authorize',
            'clause',
            'operator',
            'negate',
            'multiple',
            'options',
            'restrict',
        ));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        $queryValue = $request->query($this->getName());
        if ($this->hasMultiple() && $this->getClause()->isMultiple()) {
            $queryValue = $this->splitToMultiple($queryValue);
        }

        $transformedValue = $this->transformUsing($queryValue);
        $this->setValue($transformedValue);
        $this->setActive($this->filtering($request));

        $optionExists = false;

        foreach ($this->getOptions() as $option) {
            $isActive = $option->hasValue($this->getValue(), $this->isMultiple());
            $option->setActive($isActive);
            $optionExists = $optionExists || $isActive;
        }

        if ($this->hasOptions() && $this->isRestricted() && ! $optionExists) {
            return;
        }

        $builder->when(
            $this->isActive() && $this->isValid($transformedValue),
            fn (Builder|QueryBuilder $builder) => $this->getClause()
                ->apply($builder,
                    $this->getProperty(),
                    $this->isNegated() ? $this->getOperator()->negate() : $this->getOperator(),
                    $this->getValue()
                )
        );
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->hasMultiple(),
            'options' => $this->getOptions(),
        ]);
    }

    public function multiple(): static
    {
        $this->setClause(Clause::CONTAINS);

        return parent::multiple();
    }
}
