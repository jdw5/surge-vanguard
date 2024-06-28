<?php

namespace Conquest\Table\Sorts;

use Closure;
use Illuminate\Http\Request;
use Conquest\Table\Refiners\Refiner;
use Conquest\Table\Sorts\Contracts\Sorts;
use Conquest\Table\Sorts\Concerns\HasSortKey;
use Conquest\Table\Sorts\Concerns\HasOrderKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

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
}