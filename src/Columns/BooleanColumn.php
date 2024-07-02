<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasTruthLabels;

class BooleanColumn extends BaseColumn
{
    use HasTruthLabels;

    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
        Closure|bool $authorize = null,
        bool $hidden = false,
        bool $srOnly = false,
        Closure $transform = null,
        Closure|string $truthLabel = 'Yes',
        Closure|string $falseLabel = 'No',
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $breakpoint, $authorize, null, $hidden, $srOnly, $transform);
        $this->setType('col:boolean');
        $this->truthLabel($truthLabel);
        $this->falseLabel($falseLabel);
    }

    public static function make(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
        Closure|bool $authorize = null,
        bool $hidden = false,
        bool $srOnly = false,
        Closure $transform = null,
        Closure|string $truthLabel = 'Yes',
        Closure|string $falseLabel = 'No',
    ): static {
        return resolve(static::class, compact(
            'name',
            'label',
            'sortable',
            'searchable',
            'breakpoint',
            'authorize',
            'hidden',
            'srOnly',
            'transform',
            'truthLabel',
            'falseLabel'
        ));
    }

    public function apply(mixed $value): mixed
    {
        if ($this->canTransform()) $value = $this->transformUsing($value);
        
        if (!!$value) return $this->getTruthLabel();
        return $this->getFalseLabel();
    }
}
