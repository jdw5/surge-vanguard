<?php

namespace Jdw5\Vanguard\Refining\Sorts;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Sorts\BaseSort;
use Jdw5\Vanguard\Refining\Sorts\Concerns\HasActiveDirection;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Sorts\Concerns\SortConstants;

class ToggleSort extends BaseSort
{
    public ?string $nextDirection = null;

    protected function setUp(): void
    {
        $this->type('toggleSort');
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        /** Set the sort field */
        $this->value($request->query(SortConstants::SORT_FIELD));
        $this->direction($request->query(SortConstants::ORDER_FIELD));

        $this->nextDirection($this->getDirection());

        if (! $this->isActive()) return;
        
        $this->apply($builder, $this->getProperty(), $this->getDirection());
    }

    public function nextDirection(?string $direction): void
    {
        if (!$this->isActive()) $direction = null;

        $this->nextDirection = match ($direction) {
            null => 'asc',
            'asc' => 'desc',
            default => null,
        };
    }

    public function getNextDirection(): ?string
    {
        return $this->nextDirection;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'next_direction' => $this->getNextDirection(),
        ]);
    }
}