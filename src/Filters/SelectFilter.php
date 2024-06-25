<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Options\Option;
use Jdw5\Vanguard\Options\Concerns\HasOptions;
use Jdw5\Vanguard\Filters\BaseFilter;
use Jdw5\Vanguard\Filters\Concerns\HasMultiple;
use Jdw5\Vanguard\Filters\Concerns\IsRestrictable;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Filters\Concerns\HasClause;
use Jdw5\Vanguard\Filters\Concerns\HasOperator;
use Jdw5\Vanguard\Filters\Concerns\IsNegatable;
use Jdw5\Vanguard\Filters\Enums\Clause;

class SelectFilter extends BaseFilter
{
    use HasMultiple;
    use HasOptions;
    use IsRestrictable;
    use IsNegatable;
    use HasClause;
    use HasOperator;

    public function __construct(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        bool $multiple = false,
        array $options = [],
        bool|Closure $restrict = null,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        if ($multiple) $this->setClause(Clause::CONTAINS);
        $this->setOptions($options);
        $this->setRestrict($restrict);
    }

    public static function make(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        bool $multiple = false,
        array $options = [],
        bool|Closure $restrict = null,
    ): static {
        return new static($property, $name, $label, $authorize, $multiple, $options, $restrict);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        $raw = $request->query($this->getName());
        if ($this->getClause()->isMultiple()) $raw = $this->split($raw);

        $value = $this->validateUsing($raw);
        $this->setValue($value ?? $raw);

        $optionExists = false;

        $this->getOptions()->each(function (Option $option) use (&$optionExists) {
            $isActive = $option->hasValue($this->getValue(), $this->isMultiple());
            $option->setActive($isActive);
            $optionExists = $optionExists || $isActive;
        });

        if (!$value) return;

        if ($this->hasOptions() && $this->isRestricted() && !$optionExists) {
            $this->setActive(false);
            return;
        }

        $this->setActive($this->filtering($request));

        $builder->when(
            $this->isActive(),
            fn (Builder|QueryBuilder $builder) => $this->getClause()
                ->apply($builder, $this->getProperty(), $this->getOperator(), $this->getValue())
        );
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->isMultiple(),
            'options' => $this->getOptions(),
        ]);
    }
}