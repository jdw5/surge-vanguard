<?php

namespace Conquest\Table\Sorts;

use Conquest\Core\Concerns\IsDefault;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Sort extends BaseSort
{
    use IsDefault;

    public function setUp(): void
    {
        $this->setType('sort');
    }

    public function handle(Builder|QueryBuilder $builder, ?string $direction = null): void
    {
        $builder->orderBy(
            column: $builder instanceof Builder ? $builder->qualifyColumn($this->getProperty()) : $this->getProperty(),
            direction: $this->hasDirection() ? $this->getDirection() : $direction ?? config('table.sort.default_order', 'asc'),
        );
    }

    public function sorting(?string $sortBy, ?string $direction): bool
    {
        return $sortBy === $this->getName() && ($this->hasDirection() ? $direction === $this->getDirection() : true);
    }
}
