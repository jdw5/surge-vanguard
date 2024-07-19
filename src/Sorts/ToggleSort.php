<?php

namespace Conquest\Table\Sorts;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ToggleSort extends BaseSort
{
    public function setUp(): void
    {
        $this->setType('sort:toggle');
    }
    
    public function apply(Builder|QueryBuilder $builder, ?string $sortBy = null, ?string $orderBy = null): void
    {
        parent::apply($builder, $sortBy, $orderBy);
        $this->setDirection($this->getNextDirection($orderBy));
    }

    public function getNextDirection(?string $orderBy): ?string
    {
        if (! $this->isActive()) {
            return 'asc';
        }

        return match ($orderBy) {
            'asc' => 'desc',
            'desc' => null,
            default => 'asc',
        };
    }
}
