<?php

namespace Conquest\Table\Sorts;

use Closure;
use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Sorts\Concerns\HasDirection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

/**
 * Agnostic to the order field, considers only field with predefined direction
 * This sort can be used as a default
 */
class Sort extends BaseSort
{
    use HasDirection;
    use IsDefault;

    public function __construct(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?string $direction = null,
        bool $default = false,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setDirection($direction);
        $this->setDefault($default);
    }

    public static function make(
        string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?string $direction = null,
        bool $default = false,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'label',
            'authorize',
            'direction',
            'default'
        ));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();

        $this->setActive($this->sorting($request));

        $builder->when(
            $this->isActive(),
            function (Builder|QueryBuilder $builder) {
                $builder->orderBy(
                    column: $builder->qualifyColumn($this->getProperty()),
                    direction: $this->getDirection(),
                );
            }
        );
    }

    public function sorting(Request $request): bool
    {
        return $this->isDefault() ||
            ($request->has($this->getSortKey()) && $request->query($this->getSortKey()) === $this->getName());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'direction' => $this->getDirection(),
        ]);
    }
}
