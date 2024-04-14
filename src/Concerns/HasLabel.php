<?php

namespace Jdw5\Vanguard\Concerns;

trait HasLabel
{
    protected mixed $label;

    public function label(mixed $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): mixed
    {
        return $this->evaluate($this->label);
    }
}
