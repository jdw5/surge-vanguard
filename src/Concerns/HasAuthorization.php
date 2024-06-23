<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Adds the ability to provide conditions to include classes
 */
trait HasAuthorization
{
    protected bool|\Closure $notAuthorized = false;
    protected bool|\Closure $authorized = true;

    /**
     * Set the condition to exclude the class
     * 
     * @param bool|\Closure $condition
     * @return static
     */
    public function authorize(bool|\Closure $condition = true): static
    {
        $this->notAuthorized = $condition;
        return $this;
    }

    /**
     * Set the condition to include the class
     * 
     * @param bool|\Closure $condition
     * @return static
     */
    public function authorizeUnless(bool|\Closure $condition = true): static
    {
        $this->authorized = $condition;
        return $this;
    }

    /**
     * Determine if the class should be excluded
     * 
     * @return bool
     */
    public function authorized(): bool
    {
        if ($this->evaluate($this->notAuthorized)) {
            return true;
        }

        return !$this->evaluate($this->authorized);
    }
}