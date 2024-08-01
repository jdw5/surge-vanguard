<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Core\Primitive;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMeta;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasPlaceholder;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\IsActive;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Core\Concerns\IsHidden;
use Conquest\Core\Concerns\IsKey;
use Conquest\Table\Columns\Concerns\HasBreakpoint;
use Conquest\Table\Columns\Concerns\HasPrefix;
use Conquest\Table\Columns\Concerns\HasSort;
use Conquest\Table\Columns\Concerns\HasSuffix;
use Conquest\Table\Columns\Concerns\HasTooltip;
use Conquest\Table\Columns\Concerns\IsSearchable;
use Conquest\Table\Columns\Concerns\IsSrOnly;
use Conquest\Table\Columns\Concerns\IsToggleable;
use Exception;

abstract class BaseColumn extends Primitive
{
    use CanTransform;
    use HasBreakpoint;
    use HasLabel;
    use HasMeta;
    use HasName;
    use HasPlaceholder;
    use HasPrefix;
    use HasProperty;
    use HasSort;
    use HasSuffix;
    use HasTooltip;
    use HasType;
    use IsActive;
    use IsAuthorized;
    use IsHidden;
    use IsKey;
    use IsSearchable;
    use IsSrOnly;
    use IsToggleable;

    public function __construct(string|Closure $name, string|Closure|null $label = null)
    {
        if ($name === 'actions') {
            throw new Exception('Column name cannot be "actions"');
        }
        parent::__construct();
        $this->setName($name);
        $this->setLabel($label ?? $this->toLabel($this->getName()));
    }

    public static function make(string|Closure $name, string|Closure|null $label = null): static
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
        if ($this->hasPrefix()) {
            $value = $this->getPrefix().$value;
        }
        
        if ($this->hasSuffix()) {
            $value = $value.$this->getSuffix();
        }

        return $value;
    }
}
