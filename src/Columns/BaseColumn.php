<?php

namespace Conquest\Table\Columns;

use Closure;
use Exception;
use Conquest\Core\Primitive;
use Conquest\Core\Concerns\IsKey;
use Conquest\Core\Concerns\HasMeta;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\IsHidden;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\HasPlaceholder;
use Conquest\Core\Concerns\IsActive;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Table\Columns\Concerns\HasSort;
use Conquest\Table\Columns\Concerns\IsSrOnly;
use Conquest\Table\Columns\Concerns\HasPrefix;
use Conquest\Table\Columns\Concerns\HasSuffix;
use Conquest\Table\Columns\Concerns\IsSearchable;
use Conquest\Table\Columns\Concerns\IsToggleable;
use Conquest\Table\Columns\Concerns\HasBreakpoint;
use Conquest\Table\Columns\Concerns\HasTooltip;

abstract class BaseColumn extends Primitive
{
    use HasName;
    use HasLabel;
    use IsHidden;
    use IsAuthorized;
    use CanTransform;
    use HasBreakpoint;
    use IsSrOnly;
    use HasProperty;
    use HasSort;
    use IsKey;
    use IsSearchable;
    use IsToggleable;
    use HasType;
    use HasMeta;
    use HasProperty;
    use HasSuffix;
    use HasPrefix;
    use HasPlaceholder;
    use HasTooltip;
    use IsActive;

    public function __construct(string|Closure $name, string|Closure $label = null) {
        if ($name === 'actions') throw new Exception('Column name cannot be "actions"');
        parent::__construct();
        $this->setName($name);
        $this->setLabel($label ?? $this->toLabel($this->getName()));
    }

    public static function make(string|Closure $name, string|Closure $label = null): static 
    {
        return resolve(static::class, compact('name', 'label'));
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'hidden' => $this->isHidden(),
            'placeholder' => $this->getPlaceholder(),
            'active' => $this->isActive(),
            'tooltip' => $this->getTooltip(),
            'breakpoint' => $this->getBreakpoint(),
            'srOnly' => $this->isSrOnly(),
            
            'sort' => $this->hasSort(),
            'sorting' => $this->isSorting(),
            'direction' => $this->getSort()?->getDirection(),
            
            'meta' => $this->getMeta(),
        ];
    }

    public function apply(mixed $value): mixed
    {
        $value = $this->applyTransform($value);
        return $this->formatValue($value);
    }

    public function formatValue(mixed $value): mixed
    {
        if ($this->hasPrefix()) $value = $this->getPrefix() . $value;
        if ($this->hasSuffix()) $value = $value . $this->getSuffix();
        return $value;
    }
}
