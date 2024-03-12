<?php

namespace Jdw5\Vanguard\Refining\Concerns;

use Illuminate\Support\Collection;
use Jdw5\Vanguard\Refining\Options\Option;

trait HasOptions
{
    protected Collection $options;
    protected bool $only = false;

    public function options(...$options): static
    {
        $this->options = collect($options)->flatten();
        return $this;
    }

    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function hasOptions(): bool
    {
        return isset($this->options) && $this->options->isNotEmpty();
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

    public function inOptions(mixed $value): bool
    {
        return $this->getOptions()->map(fn($option) => $option->value)->contains($value);
    }

    public function setActiveOption(mixed $value): void
    {
        $this->options->each(fn (Option $option) => $option->active($option->getValue() == $value));
    }
}