<?php

namespace Conquest\Table\Sorts;

use Closure;
use Conquest\Table\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Sorts\Concerns\HasDirection;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ToggleSort extends BaseSort
{
    use HasDirection;

    public ?string $nextDirection = null;

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        
        $this->setActive($this->sorting($request));
        $this->setDirection($this->sanitiseOrder($request->query($this->getOrderKey())));
        
        $builder->when(
            $this->isActive(),
            function (Builder|QueryBuilder $builder) use ($request) {
                $builder->orderBy(
                    column: $builder->qualifyColumn($this->getProperty()),
                    direction: $this->getDirection(),
                );
            }
        );
    }

    public static function make(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
    ): static {
        return resolve(static::class, compact(
            'property', 
            'name', 
            'label', 
            'authorize',
        ));
    }

    public function getNextDirection(): ?string
    {
        if (!$this->isActive()) return 'asc';

        return match ($this->sanitiseOrder(request()->query($this->getOrderKey()))) {
            'asc' => 'desc',
            'desc' => null,
            default => 'asc',
        };
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'next_direction' => $this->getNextDirection(),
        ]);
    }
}