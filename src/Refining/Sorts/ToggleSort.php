<?php

namespace Jdw5\SurgeVanguard\Refining\Sorts;

use Illuminate\Http\Request;
use Jdw5\SurgeVanguard\Refining\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;

class ToggleSort extends BaseSort
{
    protected ?string $nextDirection = null;
    protected ?string $direction = null;

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        /** Set the sort field */
        $this->value($request->query(self::SORT_FIELD));
        /** Set the direction field */
        $this->direction($request->query(self::ORDER_FIELD));
        /** Update the direction to be the 3 way toggle */
        $this->nextDirection($this->getDirection());

        if ($this->isActive()) {
            return;
        }
        
        $this->apply($builder, $this->property, $this->getDirection());
    }

    public function direction(?string $direction): static
    {
        $this->direction = $direction;
        return $this;
    }

    public function nextDirection(?string $direction): static
    {
        if (!$this->isActive()) $direction = null;
        $this->nextDirection = match ($direction) {
            null => 'asc',
            'asc' => 'desc',
            default => null,
        };

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->evaluate($this->direction);
    }

    public function getNextDirection(): ?string
    {
        return $this->evaluate($this->nextDirection);
    }
}