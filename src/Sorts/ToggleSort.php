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
    
    public function apply(Builder|QueryBuilder $builder, ?string $sortBy = null, ?string $direction = null): void
    {
        parent::apply($builder, $sortBy, $direction);
        $this->setDirection($this->getNextDirection($direction));
    }

    public function getNextDirection(?string $direction): ?string
    {
        if (! $this->isActive()) {
            return 'asc';
        }

        return match ($direction) {
            'asc' => 'desc',
            'desc' => null,
            default => 'asc',
        };
    }
}
