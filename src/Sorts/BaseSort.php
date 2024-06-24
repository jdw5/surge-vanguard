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
    
    public function __construct(
            string|Closure $property, 
            string|Closure $name = null,
            string|Closure $label = null,
            bool|Closure $authorize = null,
    ) {
        $this->setProperty($property);
        $this->setName($name ?? $this->toName($property));
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        $this->setAuthorize($authorize);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        
        $this->setActive($this->sorting($request));
        
        $builder->when(
            $this->isActive(),
            function (Builder|QueryBuilder $builder) use ($request) {
                $builder->orderBy(
                    column: $builder->qualifyColumn($this->getProperty()),
                    direction: $this->sanitiseOrder($request->query($this->getOrderKey())),
                );
            }
        );
    }

    public function sorting(Request $request): bool
    {
        return $request->has($this->getSortKey()
            && $request->query($this->getSortKey()) === $this->getName()
            && $request->has($this->getOrderKey()));
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
        ];
    }


    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}