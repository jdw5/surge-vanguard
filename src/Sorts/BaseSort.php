<?php

namespace Conquest\Table\Sorts;

use Closure;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMeta;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\IsActive;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Core\Primitive;
use Conquest\Table\Contracts\Sorts;
use Conquest\Table\Sorts\Concerns\HasDirection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class BaseSort extends Primitive implements Sorts
{
    use HasDirection;
    use HasLabel;
    use HasMeta;
    use HasName;
    use HasProperty;
    use HasType;
    use IsActive;
    use IsAuthorized;

    public function __construct(string|Closure $property, string|Closure|null $name = null, string|Closure|null $label = null)
    {
        parent::__construct();
        $this->setProperty($property);
        $this->setName($name ?? $this->toName($property));
        $this->setLabel($label ?? $this->toLabel($this->getName()));
    }

    public static function make(string|Closure $property, string|Closure|null $name = null, string|Closure|null $label = null): static
    {
        return resolve(static::class, compact('property', 'name', 'label'));
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
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'meta' => $this->getMeta(),
            'active' => $this->isActive(),
            'direction' => $this->getDirection(),
        ];
    }
}
