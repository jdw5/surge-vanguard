<?php

namespace Jdw5\Vanguard\Refining\Concerns;

trait HasValue
{
    protected mixed $value = null;

    public function value(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->evaluate($this->value);
    }
}