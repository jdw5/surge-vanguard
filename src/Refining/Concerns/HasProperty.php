<?php

namespace Jdw5\Vanguard\Refining\Concerns;

trait HasProperty
{
    protected mixed $property;

    public function property(mixed $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}