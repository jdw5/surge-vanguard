<?php

namespace Jdw5\Vanguard\Refining\Concerns;

trait HasProperty
{
    /** Must resolve to a string */
    protected string|\Closure $property;

    /**
     * Set the property to be used.
     * 
     * @param string|\Closure $property
     * @return static
     */
    public function property(string|\Closure $property): static
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
    protected function setProperty(string|\Closure $property): void
    {
        $this->property = $property;
    }

    /**
     * Get the property to be used.
     * 
     * @return string
     */
    public function getProperty(): string
    {
        return $this->evaluate($this->property);
    }
}