<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Options\Option;
use Jdw5\Vanguard\Options\Concerns\HasOptions;
use Jdw5\Vanguard\Filters\BaseFilter;
use Jdw5\Vanguard\Filters\Concerns\HasMultiple;
use Jdw5\Vanguard\Filters\IsRestrictable;
use Illuminate\Database\Query\Builder as QueryBuilder;

class SelectFilter extends BaseFilter
{
    use HasMultiple;
    use HasOptions;
    use IsRestrictable;

    public function __construct(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        bool|Closure $multiple = null,
        array $options = [],
        bool|Closure $restrict = null,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setMultiple($multiple);
        $this->setOptions($options);
        $this->setRestrict($restrict);
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

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->isMultiple(),
            'options' => $this->getOptions(),
        ]);
    }

    public function updateOptionActivity(mixed $value): void
    {
        $this->setValue(explode(',', $value));

        if ($this->hasOptions()) {
            $this->getOptions()->each(fn (Option $option) => $option->active(\in_array($option->getValue(), $this->getValue())));
        }
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $method = match ($this->getOperator()) {
            '!=' => 'whereNotIn',
            default => 'whereIn',
        };

        $builder->{$method}($property, $value, $this->getQueryBoolean());
        return;        
    }
}