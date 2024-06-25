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
    protected array $options = [];

    /**
     * Supply a list of options to be used for the filter
     * 
     * @param mixed ...$options
     * @return static
     */
    public function options(...$options): static
    {
        $this->options = array_map(function (mixed $option) {
            if ($option instanceof Option) return $option;
            return Option::make($option);
        }, $options);

        return $this;
    }

    /**
     * Get the options
     * 
     * @return array
     */
    public function getOptions(): array
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
        return !empty($this->options);
    }

    /**
     * Check if the filter does not have options
     * 
     * @return bool
     */
    public function doesNotHaveOptions(): bool
    {
        return !$this->hasOptions();
    }

}