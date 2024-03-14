<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Trait IsIncludable
 * 
 * Adds the ability to provide conditions to include classes
 * 
 * @property bool|\Closure $isExcluded
 * @property bool|\Closure $isIncluded
 */
trait IsIncludable
{
    protected bool|\Closure $isExcluded = false;
    protected bool|\Closure $isIncluded = true;

    /**
     * Set the condition to exclude the class
     * 
     * @param bool|\Closure $condition
     * @return static
     */
    public function exclude(bool|\Closure $condition = true): static
    {
        $this->isExcluded = $condition;

        return $this;
    }

    /**
     * Set the condition to include the class
     * 
     * @param bool|\Closure $condition
     * @return static
     */
    public function include(bool|\Closure $condition = true): static
    {
        $this->isIncluded = $condition;
        return $this;
    }

    /**
     * Determine if the class should be excluded
     * 
     * @return bool
     */
    public function isExcluded(): bool
    {
        if ($this->evaluate($this->isExcluded)) {
            return true;
        }

        return !$this->evaluate($this->isIncluded);
    }
}
