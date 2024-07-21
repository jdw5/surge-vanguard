<?php

namespace Conquest\Table\Sorts;

use Closure;
use Conquest\Core\Concerns\CanAuthorize;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMetadata;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\IsActive;
use Conquest\Core\Primitive;
use Conquest\Table\Contracts\Sorts;
use Conquest\Table\Sorts\Concerns\HasDirection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class BaseSort extends Primitive implements Sorts
{
    use HasDirection;
    use CanAuthorize;
    use HasLabel;
    use HasMetadata;
    use HasName;
    use HasType;
    use IsActive;
    use HasProperty;

    public function __construct(
        string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string $direction = null,
        array $metadata = null,
    ) {
        parent::__construct();
        $this->setProperty($property);
        $this->setName($name ?? $this->toName($property));
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        $this->setAuthorize($authorize);
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
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
            'direction' => $this->getDirection(),
        ];
    }
}
