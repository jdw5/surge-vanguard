<?php

namespace Jdw5\Vanguard\Refining\Concerns;

trait HasDefault
{
    protected mixed $default = null;

    public function default(mixed $value = true): static
    {
        $this->default = $value;

        return $this;
    }

    public function getDefaultValue()
    {
        return $this->evaluate($this->default);
    }
}
