<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

trait HasQueryBoolean
{
    protected string $queryBoolean = 'and';

    public function queryBoolean(string $boolean): static
    {
        $this->queryBoolean = $boolean;

        return $this;
    }

    public function or(): static
    {
        return $this->queryBoolean('or');
    }

    public function and(): static
    {
        return $this->queryBoolean('and');
    }

    public function getQueryBoolean(): string
    {
        return $this->queryBoolean;
    }
}