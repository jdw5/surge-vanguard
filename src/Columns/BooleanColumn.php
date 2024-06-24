<?php

namespace Jdw5\Vanguard\Columns;

use Closure;
use Jdw5\Vanguard\Enums\Breakpoint;
use Jdw5\Vanguard\Columns\Concerns\HasTruthLabels;

class TextColumn extends Column
{
    use HasTruthLabels;

    public function __construct(
        string|Closure $name, 
        string|Closure $label = null,
        bool $sortable = false,
        bool $searchable = false,
        bool $toggleable = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
        Closure|bool $authorize = null,
        bool $asHeading = true,
        bool $srOnly = false,
        Closure $transform = null,
        Closure|string $truthLabel = 'Yes',
        Closure|string $falseLabel = 'No',
    ) {
        parent::__construct($name, $label, $sortable, $searchable, $toggleable, $breakpoint, $authorize, null, $asHeading, $srOnly, $transform);
        $this->setType('col:boolean');
        $this->truthLabel($truthLabel);
        $this->falseLabel($falseLabel);
    }

    public function apply(mixed $value): mixed
    {
        if ($this->hasTransform()) $value = $this->transformUsing($value);
        
        if (!!$value) return $this->getTruthLabel();
        return $this->getFalseLabel();
    }
}
