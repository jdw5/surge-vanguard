<?php

namespace Jdw5\Vanguard\Concerns;

trait HasLabel
{
    protected string|\Closure $label;

    public function label(string|\Closure $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->evaluate($this->label);
    }
}
