<?php

namespace Conquest\Table\Columns;

use Closure;
use Exception;
use Conquest\Core\Primitive;
use Conquest\Core\Concerns\IsKey;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\IsHidden;
use Conquest\Core\Concerns\HasMetadata;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Core\Concerns\CanAuthorize;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Table\Columns\Concerns\HasSort;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasFallback;
use Conquest\Table\Columns\Concerns\IsSearchable;
use Conquest\Table\Columns\Concerns\HasBreakpoint;
use Conquest\Table\Concerns\Remember\IsToggleable;
use Conquest\Table\Columns\Concerns\HasAccessibility;

abstract class BaseColumn extends Primitive
{
    use HasName;
    use HasLabel;
    use IsHidden;
    use HasFallback;
    use CanAuthorize;
    use CanTransform;
    use HasBreakpoint;
    use HasAccessibility;
    use HasProperty;
    use HasSort;
    use IsKey;
    use IsSearchable;
    use IsToggleable;
    use HasType;
    use HasMetadata;

    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $hidden = false,
        mixed $fallback = null,
        Closure|bool $authorize = null,
        Closure $transform = null,
        Breakpoint|string $breakpoint = null,
        bool $srOnly = false,
        bool $sortable = false,
        bool $searchable = false,
        bool $active = true,
        bool $isKey = false,
        array $metadata = null,
    ) {
        if ($name === 'actions') throw new Exception('Column name cannot be "actions"');
        parent::__construct();
        $this->setName($name);
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        if ($sortable) $this->setSort();
        $this->setSearchable($searchable);
        $this->setBreakpoint($breakpoint);
        $this->setAuthorize($authorize);
        $this->setFallback($fallback);
        $this->setHidden($hidden);
        $this->setSrOnly($srOnly);
        $this->setTransform($transform);
        $this->setToggledOn($active);
        $this->setKey($isKey);
        $this->setMetadata($metadata);
    }

    /**
     * Convert the column to an array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'fallback' => $this->getFallback(),
            'active' => $this->isToggledOn(),
            'hidden' => $this->isHidden(),
            'breakpoint' => $this->getBreakpoint(),
            'srOnly' => $this->isSrOnly(),
            'sort' => $this->hasSort(),
            'sorting' => $this->isSorting(),
            'direction' => $this->getSort()?->getDirection(),
        ];
    }

    public function apply(mixed $value): mixed
    {
        if (is_null($value)) return $this->getFallback();
        return $this->transformUsing($value);
    }
}
