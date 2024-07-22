<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\CanValidate;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Table\Exceptions\CannotResolveNameFromProperty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Request;

abstract class PropertyFilter extends BaseFilter
{
    use HasProperty;
    use CanTransform;
    use CanValidate;

    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        array $metadata = null,
    ) {
        if (is_array($property) && is_null($name)) throw new CannotResolveNameFromProperty($property);
        
        $name = $name ?? $this->toName($this->evaluate($property));
        parent::__construct(
            name: $name, 
            label: $label, 
            authorize: $authorize, 
            metadata:$metadata
        );
        $this->setTransform($transform);
        $this->setValidator($validator);
        $this->setProperty($property);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->transformUsing($this->getValueFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value));

        $builder->when(
            $this->isActive() && $this->validateUsing($value),
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
