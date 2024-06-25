<?php

namespace Jdw5\Vanguard\Filters;

use Closure;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Refiner\Refiner;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Filters\Contracts\Filters;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Columns\Concerns\HasTransform;
use Jdw5\Vanguard\Concerns\HasValue;
use Jdw5\Vanguard\Filters\Concerns\HasValidator;
use Jdw5\Vanguard\Filters\Exceptions\CannotResolveNameFromProperty;

abstract class BaseFilter extends Refiner implements Filters
{
    use HasValue;
    use HasValidator;
    use HasTransform;

    public function __construct(
        array|string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        Closure $validator = null,
        Closure $transform = null,
    ) {
        if (is_array($property) && is_null($name)) throw new CannotResolveNameFromProperty($property);
        parent::__construct($property, $name, $label, $authorize);
        $this->setValidator($validator);
        $this->setTransform($transform);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request(); 
        $value = $this->transformUsing($request->query($this->getName()));
        $this->setValue($value);
        $this->setActive($this->filtering($request));

        $builder->when(
            $this->isActive() && $this->validateUsing($value),
            function (Builder|QueryBuilder $builder) {
                $builder->where(
                    column: $builder->qualifyColumn($this->getProperty()),
                    value: $this->getValue(),
                );
            }
        );        
    }

    public function filtering(Request $request): bool
    {
        return $request->has($this->getName()) && !is_null($request->query($this->getName()));
    }

    /**
     * Convert the filter to an array representation
     * 
     * @return array
     */
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
    /**
     * Serialize the filter for JSON.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}