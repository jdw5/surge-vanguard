<?php

namespace Jdw5\Vanguard\Filters;

class BooleanFilter extends Filter
{
    protected string $confirmText = 'Yes';
    protected string $cancelText = 'No';
    public function __construct(
        string $label,
        string $name,
        bool $default = false,
        bool $value = false,
    ) {
        parent::__construct($label, $name, $default, $value);
    }

    public function apply($query)
    {
        if ($this->value) {
            return $query->where($this->name, true);
        }

        return $query;
    }
}