<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Core\Concerns\CanAuthorize;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\HasMetadata;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\IsHidden;
use Conquest\Core\Concerns\IsKey;
use Conquest\Table\Columns\Concerns\HasFallback;
use Conquest\Table\Columns\Concerns\HasSort;
use Conquest\Table\Columns\Concerns\IsSearchable;
use Conquest\Table\Columns\Enums\Breakpoint;

class Column extends BaseColumn
{
    use CanAuthorize;
    use CanTransform;
    use HasFallback;
    use HasMetadata;
    use HasName;
    use HasSort;
    use IsHidden;
    use IsKey;

    // use IsSortable;
    use IsSearchable;

    public function __construct(
        string|Closure $name,
        string|Closure|null $label = null,
        bool $sortable = false,
        bool $searchable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
        Closure|bool|null $authorize = null,
        mixed $fallback = null,
        bool $hidden = false,
        bool $srOnly = false,
        ?Closure $transform = null,
    ) {
        $this->setName($name);
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        if ($sortable) {
            $this->setSort();
        }
        $this->setSearchability($searchable);
        $this->setBreakpoint($breakpoint);
        $this->setAuthorize($authorize);
        $this->setFallback($fallback);
        $this->setHidden($hidden);
        $this->setSrOnly($srOnly);
        $this->setTransform($transform);
        $this->setType('col:');
    }

    /**
     * Statically create the column
     */
    public static function make(
        string $name,
        ?string $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
        Closure|bool|null $authorize = null,
        mixed $fallback = null,
        bool $asHeading = true,
        bool $srOnly = false,
        ?Closure $transform = null,
    ): static {
        return resolve(static::class, compact(
            'name',
            'label',
            'sortable',
            'searchable',
            'toggleable',
            'breakpoint',
            'authorize',
            'fallback',
            'asHeading',
            'srOnly',
            'transform'
        ));
    }

    /**
     * Convert the column to an array
     */
    public function toArray(): array
    {
        return [
            /** Column information */
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'fallback' => $this->getFallback(),

            /** Display options for frontend */
            'hidden' => $this->isShown(),
            'breakpoint' => $this->getBreakpoint(),
            'srOnly' => $this->isSrOnly(),

            /** Sorting options */
            'has_sort' => $this->hasSort(),
            'active' => $this->isSorting(),
            'direction' => $this->getSort()?->getDirection(),
            'next_direction' => $this->getSort()?->getNextDirection(),
            'sort_field' => $this->getSort()?->getName(),
        ];
    }

    public function apply(mixed $value): mixed
    {
        if (is_null($value)) {
            return $this->getFallback();
        }
        if ($this->canTransform()) {
            return $this->transformUsing($value);
        }

        return $value;
    }
}
