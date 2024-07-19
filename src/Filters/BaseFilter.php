<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\CanValidate;
use Conquest\Core\Concerns\HasValue;
use Conquest\Table\Filters\Contracts\Filters;
use Conquest\Table\Filters\Exceptions\CannotResolveNameFromProperty;
use Conquest\Table\Refiners\Refiner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Request;

abstract class BaseFilter extends Refiner implements Filters
{
    use CanTransform;
    use CanValidate;
    use HasValue;

    public function __construct(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        array $metadata = null,
    ) {
        if (is_array($property) && is_null($name)) throw new CannotResolveNameFromProperty($property);
        parent::__construct($property, $name, $label, $authorize, $metadata);
        $this->setValidator($validator);
        $this->setTransform($transform);
    }

    public function getValueFromRequest(): mixed
    {
        return Request::input($this->getName(), null);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->transformUsing($this->getValueFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value) && $this->validateUsing($value));

        $builder->when(
            $this->isActive(),
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

    public function filtering(mixed $value): bool
    {
        return !is_null($value);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'active' => $this->isActive(),
            'value' => $this->getValue(),
            'metadata' => $this->getMetadata(),
        ];
    }
}
