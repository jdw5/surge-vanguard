<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\CanValidate;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Table\Exceptions\CannotResolveNameFromProperty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class PropertyFilter extends BaseFilter
{
    use HasProperty;
    use CanValidate;

    public function __construct(array|string|Closure $property, string|Closure $name = null, string|Closure $label = null)
    {
        if (is_array($property) && is_null($name)) {
            throw new CannotResolveNameFromProperty($property);
        }
        $this->setProperty($property);
        parent::__construct($name ?? $this->toName($this->evaluate($property)), $label);
    }

    public static function make(array|string|Closure $property, string|Closure $name = null, string|Closure $label = null): static
    {
        return resolve(static::class, compact('property', 'name', 'label'));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->applyTransform($this->getValueFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value));

        $builder->when(
            $this->isActive() && $this->isValid($value),
            fn (Builder|QueryBuilder $builder) => $this->handle($builder),
        );
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $builder->where(
            column: $builder->qualifyColumn($this->getProperty()),
            value: $this->getValue(),
        );
    }
}
