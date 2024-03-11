<?php

namespace Jdw5\Vanguard\Refining\Concerns;

trait HasOptions
{
    protected array $options = [];
    protected bool $only = false;

    public function options(array $options): static
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasOptions(): bool
    {
        return count($this->options) > 0;
    }

    public function only(): static
    {
        $this->only = true;
        return $this;
    }

    public function isOnly(): bool
    {
        return $this->only;
    }

}