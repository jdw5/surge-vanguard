<?php

namespace Jdw5\SurgeVanguard\Concerns;

trait HasAlias
{
    protected string|\Closure|null $alias = null;

    public function alias(null|string|\Closure $alias): static
    {
        $this->alias = $alias;
        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->evaluate($this->alias);
    }
}
