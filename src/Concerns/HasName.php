<?php

namespace Jdw5\Vanguard\Concerns;

trait HasName
{
    protected string|\Closure $name;

    public function name(string|\Closure $name): static
    {
        $this->setName($name);
        return $this;
    }

    protected function setName(string|\Closure $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->evaluate($this->name);
    }
}
