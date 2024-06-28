<?php

namespace Conquest\Table\Sorts;

use Closure;
use Illuminate\Http\Request;
use Conquest\Table\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Conquest\Table\Sorts\Concerns\HasDirection;

/** Only applied when sort AND order match  */
class AnisotropicSort extends BaseSort
{
    use HasDirection;

    public function __construct(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string $direction = null,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setDirection($direction);
    }

    public function sorting(Request $request): bool
    {
        return $request->has($this->getSortKey()
            && $request->query($this->getSortKey()) === $this->getName()
            && $request->has($this->getOrderKey())
            && $request->query($this->getOrderKey()) === $this->getDirection()
        );
    }
    
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'direction' => $this->getDirection(),
        ]);
    }
}