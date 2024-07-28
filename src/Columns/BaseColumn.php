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
use Conquest\Core\Concerns\IsActive;
use Conquest\Table\Columns\Concerns\HasSort;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\IsSearchable;
use Conquest\Table\Columns\Concerns\HasBreakpoint;
use Conquest\Table\Columns\Concerns\HasAccessibility;
use Conquest\Table\Columns\Concerns\HasPrefix;
use Conquest\Table\Columns\Concerns\HasSuffix;

abstract class BaseColumn extends Primitive
{
    use HasName;
    use HasLabel;
    use IsHidden;
    use CanAuthorize;
    use CanTransform;
    use HasBreakpoint;
    use HasAccessibility;
    use HasProperty;
    use HasSort;
    use IsKey;
    use IsSearchable;
    use IsActive;
    use HasType;
    use HasMetadata;
    use HasProperty;
    use HasSuffix;
    use HasPrefix;

    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $hidden = false,
        Closure|bool $authorize = null,
        Closure $transform = null,
        Breakpoint|string $breakpoint = null,
        bool $srOnly = false,
        bool $sortable = false,
        bool $searchable = false,
        bool $active = true,
        bool $key = false,
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
        $this->setHidden($hidden);
        $this->setSrOnly($srOnly);
        $this->setTransform($transform);
        $this->setActive($active);
        $this->setKey($key);
        $this->setMetadata($metadata);
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'hidden' => $this->isHidden(),
            'active' => $this->isActive(),
            'breakpoint' => $this->getBreakpoint(),
            'srOnly' => $this->isSrOnly(),
            'sort' => $this->hasSort(),
            'sorting' => $this->isSorting(),
            'direction' => $this->getSort()?->getDirection(),
            'metadata' => $this->getMetadata(),
        ];
    }

    public function apply(mixed $value): mixed
    {
        $value = $this->transformUsing($value);
        return $this->modifyAsString($value);
    }

    protected function modifyAsString(mixed $value): string
    {
        if ($this->hasPrefix()) $value = $this->getPrefix() . $value;
        if ($this->hasSuffix()) $value = $value . $this->getSuffix();
        return $value;

    }
}
