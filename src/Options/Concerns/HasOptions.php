<?php

namespace Jdw5\Vanguard\Options\Concerns;

use Illuminate\Support\Collection;
use Jdw5\Vanguard\Options\Option;

/**
 * Define options on a class to be used for refinement
 */
trait HasOptions
{
    /** The options available */
    protected ?Collection $options = null;
    /** If the query should be ignored when not listed values are supplied */
    protected bool $only = false;

    /**
     * Supply a list of options to be used for the filter
     * 
     * @param mixed ...$options
     * @return static
     */
    public function options(...$options): static
    {
        $this->options = collect($options)->flatten()->map(function ($option) {
            if ($option instanceof Option) return $option;            
            return Option::make($option);
        })->flatten();

        return $this;
    }

    /**
     * Get the options
     * 
     * @return Collection|null
     */
    public function getOptions(): ?Collection
    {
        return $this->options;
    }

    /**
     * Check if the filter has options
     * 
     * @return bool
     */
    public function hasOptions(): bool
    {
        return ! \is_null($this->getOptions()) && $this->getOptions()->isNotEmpty();
    }

    /**
     * Check if the filter does not have options
     * 
     * @return bool
     */
    public function doesNotHaveOptions(): bool
    {
        return ! $this->hasOptions();
    }

    /**
     * Set the filter to only accept the options provided
     * 
     * @return static
     */
    public function only(): static
    {
        $this->setOnly(true);
        return $this;
    }

    /**
     * Set the filter to accept all options
     * 
     * @return static
     */
    public function all(): static
    {
        $this->setOnly(false);
        return $this;
    }

    /**
     * Set whether the filter should only accept the options provided
     * 
     * @param bool $only
     * @return void
     */
    protected function setOnly(bool $only): void
    {
        $this->only = $only;
    }

    /**
     * Get whether the filter should only accept the options provided
     * 
     * @return bool
     */
    public function getOnly(): bool
    {
        return $this->only;
    }

    /**
     * Get whether the filter ignores invalid options
     * 
     * @return bool
     */
    public function ignoresInvalidOptions(): bool
    {
        return $this->getOnly();
    }

    /**
     * Get whether the filter accepts all options
     */
    public function acceptsAllOptions(): bool
    {
        return ! $this->ignoresInvalidOptions();
    }

    /**
     * Check if the value is a valid option
     * 
     * @param mixed $value
     * @return bool
     */
    public function isValidOption(mixed $value): bool
    {
        if ($this->doesNotHaveOptions()) return true;
        return $this->getOptions()->map(fn (Option $option) => $option->getValue())->contains($value);
    }

    /**
     * Check if the value is an invalid option
     * 
     * @param mixed $value
     * @return bool
     */
    public function isInvalidOption(mixed $value): bool
    {
        return ! $this->isValidOption($value);
    }

    /**
     * Set the options active status
     * 
     * @param mixed $value
     * @return void
     */
    public function updateOptionActivity(mixed $value): void
    {
        if (!$this->hasOptions()) return;
        $this->options->each(fn (Option $option) => $option->active($option->getValue() == $value));
    }
}