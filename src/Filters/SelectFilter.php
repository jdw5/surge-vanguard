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
use Jdw5\Vanguard\Filters\Concerns\IsNegatable;

class SelectFilter extends BaseFilter
{
    use HasMultiple;
    use HasOptions;
    use IsRestrictable;
    use IsNegatable;

    public function __construct(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        bool|Closure $multiple = null,
        array $options = [],
        bool|Closure $restrict = null,
        bool|Closure $negate = null,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setMultiple($multiple);
        $this->setOptions($options);
        $this->setRestrict($restrict);
        $this->setNegate($negate);
    }

    public static function make(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        bool|Closure $multiple = null,
        array $options = [],
        bool|Closure $restrict = null,
    ): static {
        return new static($property, $name, $label, $authorize, $multiple, $options, $restrict);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        $raw = $request->query($this->getName());
        if ($this->isMultiple()) $raw = $this->split($raw);

        $value = $this->validateUsing($raw);
        $this->setValue($value ?? $raw);

        if (!$value) return;

        // Check the options -> only active if value is an option and options restricted
        if ($this->hasOptions() && $this->isRestricted()) {
            $optionExists = false;

            $this->getOptions()->each(function (Option $option) use (&$optionExists) {
                $isActive = $option->hasValue($this->getValue(), $this->isMultiple());
                $option->setActive($isActive);
                $optionExists = $optionExists || $isActive;
            });

            // If still false, it's not a valid option
            if (!$optionExists) return $this->setActive(false);
        }

        $this->setActive($this->filtering($request));

        $builder->when(
            $this->isActive(),
            function (Builder|QueryBuilder $builder) {
                $builder->{$this->isNegated() ? 'whereNotIn' : 'whereIn'}(
                    $this->getProperty(),
                    $this->getValue()
                );
            }
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