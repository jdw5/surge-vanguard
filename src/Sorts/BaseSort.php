<?php

namespace Conquest\Table\Sorts;

use Closure;
use Conquest\Table\Refiners\Refiner;
use Conquest\Table\Sorts\Concerns\HasOrderKey;
use Conquest\Table\Sorts\Concerns\HasSortKey;
use Conquest\Table\Sorts\Contracts\Sorts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

abstract class BaseSort extends Refiner implements Sorts
{
    use HasOrderKey;
    use HasSortKey;

    public function __construct(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
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
