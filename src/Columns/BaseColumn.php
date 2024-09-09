<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasMeta;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasPlaceholder;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\IsActive;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Core\Concerns\IsHidden;
use Conquest\Core\Concerns\IsKey;
use Conquest\Core\Primitive;
use Conquest\Table\Columns\Concerns\HasBreakpoint;
use Conquest\Table\Columns\Concerns\HasPrefix;
use Conquest\Table\Columns\Concerns\HasSuffix;
use Conquest\Table\Columns\Concerns\HasTooltip;
use Conquest\Table\Columns\Concerns\IsSortable;
use Conquest\Table\Columns\Concerns\IsSrOnly;
use Conquest\Table\Columns\Concerns\IsToggleable;
use InvalidArgumentException;

abstract class BaseColumn extends Primitive
{
    use CanTransform;
    use HasBreakpoint;
    use HasLabel;
    use HasMeta;
    use HasName;
    use HasPlaceholder;
    use HasPrefix;
    use HasSuffix;
    use HasTooltip;
    use HasType;
    use IsActive;
    use IsAuthorized;
    use IsHidden;
    use IsKey;
    use IsSortable;
    use IsSrOnly;
    use IsToggleable;

    public function __construct(string|Closure $name, string|Closure|null $label = null)
    {
        if ($name === 'actions') {
            throw new InvalidArgumentException('Column name cannot be "actions"');
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
            'type' => $this->getType(),
            'hidden' => $this->isHidden(),
            'placeholder' => $this->getPlaceholder(),
            'tooltip' => $this->getTooltip(),
            'breakpoint' => $this->getBreakpoint(),
            'sr' => $this->isSrOnly(),

            'toggleable' => $this->isToggleable(),
            'active' => $this->isToggledOn(),

            'sortable' => $this->isSortable(),
            'sorting' => $this->isSorting(),
            'direction' => $this->getSort()?->getDirection(),

            'meta' => $this->getMeta(),
            'prefix' => $this->getPrefix(),
            'suffix' => $this->getSuffix(),
        ];
    }

    public function apply(mixed $value): mixed
    {
        $value = $this->applyTransform($value);

        return $this->formatValue($value);
    }

    public function formatValue(mixed $value): mixed
    {
        return $value;
    }
}
