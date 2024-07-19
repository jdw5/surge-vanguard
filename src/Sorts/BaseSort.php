<?php

namespace Conquest\Table\Sorts;

use Closure;
use Conquest\Table\Refiners\Refiner;
use Conquest\Table\Sorts\Concerns\HasDirection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Request;

abstract class BaseSort extends Refiner
{
    use HasDirection;

    public function __construct(
        string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string $direction = null,
        array $metadata = null,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setMetadata($metadata);
        $this->setDirection($direction);
    }

    public function apply(Builder|QueryBuilder $builder, ?string $sortBy = null, ?string $direction = null): void
    {
        $this->setActive($this->sorting($sortBy, $direction));

        $builder->when(
            $this->isActive(),
            fn (Builder|QueryBuilder $builder) => $this->handle($builder, $direction),
        );
    }

    public function handle(Builder|QueryBuilder $builder, ?string $direction = null): void
    {
        $builder->orderBy(
            column: $builder->qualifyColumn($this->getProperty()),
            direction: $direction ?? config('table.sort.default_order', 'asc'),
        );
    }

    public function sorting(?string $sortBy, ?string $direction): bool
    {
        return $sortBy === $this->getName();
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'direction' => $this->getDirection(),
        ]);
    }
}
