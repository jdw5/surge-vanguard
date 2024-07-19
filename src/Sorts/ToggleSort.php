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
    
    public function apply(Builder|QueryBuilder $builder, ?string $sortBy, ?string $orderBy): void
    {
        $this->setActive($this->sorting($sortBy, $orderBy));
        $this->setDirection($this->getNextDirection($orderBy));

        $builder->when(
            $this->isActive(),
            function (Builder|QueryBuilder $builder) use ($orderBy) {
                $builder->orderBy(
                    column: $builder->qualifyColumn($this->getProperty()),
                    direction: $orderBy,
                );
            }
        );
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
