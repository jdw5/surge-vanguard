<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasBooleanLabels;

class BooleanColumn extends BaseColumn
{
    use HasBooleanLabels;

    public function setUp(): void
    {
        $this->setType('col:bool');
    }

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
        string|Closure $truthLabel = null,
        string|Closure $falseLabel = null,
        array $meta = null,
    ) {
        parent::__construct(...compact(
            'name',
            'label',
            'hidden',
            'authorize',
            'transform',
            'breakpoint',
            'srOnly',
            'sortable',
            'searchable',
            'active',
            'meta',
        ));
        $this->setTruthLabel($truthLabel);
        $this->setFalseLabel($falseLabel);
    }

    public static function make(
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
        string|Closure $truthLabel = 'Active',
        string|Closure $falseLabel = 'Inactive',
        array $meta = null,
    ): static {
        return resolve(static::class, compact(
            'name',
            'label',
            'hidden',
            'authorize',
            'transform',
            'breakpoint',
            'srOnly',
            'sortable',
            'searchable',
            'active',
            'truthLabel',
            'falseLabel',
            'meta',
        ));
    }

    public function apply(mixed $value): mixed
    {
        if ($this->canTransform()) $value = $this->transformUsing($value);
        return !!$value ? $this->getTruthLabel() : $this->getFalseLabel();
    }
}
