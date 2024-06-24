<?php

namespace Jdw5\Vanguard\Sorts;

use Closure;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Refiner\Refiner;
use Jdw5\Vanguard\Concerns\IsDefault;
use Jdw5\Vanguard\Sorts\Contracts\Sorts;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Sorts\Concerns\HasSortKey;
use Jdw5\Vanguard\Sorts\Concerns\HasOrderKey;
use Jdw5\Vanguard\Sorts\Sorts\Concerns\HasDirection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Refining\Sorts\Concerns\SortConstants;

abstract class BaseSort extends Refiner implements Sorts
{
    use HasSortKey;
    use HasOrderKey;
    
    public static function make(
            string|Closure $property, 
            string|Closure $name = null,
            string|Closure $label = null,
            bool|Closure $authorize = null,
    ): static {
        return new static($property, $name, $label, $authorize);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {        
        // Doesn't need
        $this->value($request->query(SortConstants::SORT_FIELD));

        if ($this->isActive() || (is_null($this->getValue()) && $this->isDefault())) {
            $this->apply($builder, $this->getProperty(), $this->getDirection());
            $builder->orderBy(
                column: $builder->qualifyColumn($property),
                direction: $direction,
            );
        }         
    }

    public function isActive(): bool
    {
        return $this->isActiveSort();
    }

    /**
     * Compute whether the sort is active
     * 
     * @return bool
     */
    public function isActiveSort(): bool
    {
        return ($this->getValue() === $this->getName()) || ($this->isDefault() && \is_null($this->getValue()));
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
        ];
    }
}