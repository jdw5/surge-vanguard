<?php

namespace Jdw5\Vanguard\Concerns;

use Closure;

trait HasProperty
{
    /** Must resolve to a string */
    protected array|string|Closure $property = null;

    /**
     * Set the property to be used.
     * 
     * @param string|\Closure $property
     * @return static
     */
    public function property(array|string|Closure $property): static
    {
        $this->setProperty($property);
        return $this;
    }

    /**
     * Set the property to be used quietly.
     * 
     * @param string|\Closure $property
     * @return void
     */
    protected function setProperty(array|string|Closure $property): void
    {
        $this->property = $property;
    }

    /**
     * Get the property to be used.
     * 
     * @return string
     */
    public function getProperty(): string|array
    {
        return $this->evaluate($this->property);
    }

    public function hasProperty(): bool
    {
        return !is_null($this->property);
    }
}