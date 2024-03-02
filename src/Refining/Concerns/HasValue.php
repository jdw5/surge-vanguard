<?php

namespace Jdw5\SurgeVanguard\Refining\Concerns;

trait HasValue
{
    protected mixed $value = null;

    public function value(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    protected function getValue(): mixed
    {
        return $this->evaluate($this->value);
    }
}