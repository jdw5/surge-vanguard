<?php

namespace Jdw5\Vanguard\Refining\Sorts;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Sorts\Concerns\HasDirection;

class Sort extends BaseSort
{
    use HasDirection;

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->activeDirection($request->query(self::ORDER_FIELD));
        $this->value($request->query(self::SORT_FIELD));

        if ($this->getValue() !== $this->getName() || $this->getActiveDirection() !== $this->getDirection()) {
            return;
        }
        
        $this->apply($builder, $this->property, $this->getDirection());
    }

    public function isActive(): bool
    {
        return $this->sortIsActive() && $this->orderIsActive();
    }

    public function orderIsActive(): bool
    {
        return $this->getDirection() === $this->getActiveDirection();
    }    

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'direction' => $this->getDirection(),
        ]);
    }



}