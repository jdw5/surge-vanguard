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

    public function __construct(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?array $meta = null,
    ) {
        parent::__construct(
            property: $property,
            name: $name,
            label: $label,
            authorize: $authorize,
            meta: $meta
        );
    }

    public static function make(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?array $meta = null,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'label',
            'authorize',
            'meta',
        ));
    }

    public function apply(Builder|QueryBuilder $builder, ?string $sortBy = null, ?string $direction = null): void
    {
        $this->setDirection($direction);
        parent::apply($builder, $sortBy, $direction);
    }

    public function getNextDirection(?string $direction = null): ?string
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

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'direction' => $this->getNextDirection($this->getDirection()),
        ]);
    }
}
