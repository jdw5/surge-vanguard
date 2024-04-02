<?php

namespace Jdw5\Vanguard\Refining\Concerns;

use Illuminate\Support\Collection;
use Jdw5\Vanguard\Refining\Options\Option;

trait HasOptions
{
    protected ?Collection $options = null;
    protected bool $only = false;

    public function options(...$options): static
    {
        $this->options = collect($options)->map(function ($option) {
            if ($option instanceof Option) return $option;            
            return Option::make($option);
        })->flatten();

        // dd($this->options);
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

    public function validOnly(): bool
    {
        return $this->only;
    }

    public function isValidOption(mixed $value): bool
    {
        if (!$this->hasOptions()) return true;
        return $this->getOptions()->map(fn (Option $option) => $option->getValue())->contains($value);
    }

    public function setActiveOption(mixed $value): void
    {
        if (!$this->hasOptions()) return;
        $this->getOptions()->each(fn (Option $option) => $option->active($option->getValue() == $value));
    }
}