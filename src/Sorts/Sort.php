<?php

namespace Conquest\Table\Sorts;

use Closure;
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

    public function __construct(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?string $direction = null,
        bool $default = false,
        ?array $meta = null,
    ) {
        parent::__construct($property, $name, $label, $authorize, $direction, $meta);
        $this->setDefault($default);
    }

    public static function make(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?string $direction = null,
        bool $default = false,
        ?array $meta = null,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'label',
            'authorize',
            'direction',
            'default',
            'meta',
        ));
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
