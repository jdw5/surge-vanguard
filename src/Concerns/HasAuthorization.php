<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Adds the ability to provide conditions to include classes
 */
trait HasAuthorization
{
    protected bool|\Closure $authorized = true;

    protected function setAuthorize(bool|\Closure|null $condition): void
    {
        if ($condition === null) {
            return;
        }

        $this->authorized = $condition;
    }
    /**
     * Set the condition to exclude the class
     * 
     * @param bool|\Closure $condition
     * @return static
     */
    public function authorize(bool|\Closure $condition = true): static
    {
        $this->setAuthorize($condition);
        return $this;
    }
    /**
     * Determine if the class should be excluded
     * 
     * @return bool
     */
    public function authorized(): bool
    {
        return $this->evaluate($this->authorized);
    }

    /** Aliases */
    public function allowed(): bool
    {
        return $this->authorized();
    }

    public function isAuthorized(): bool
    {
        return $this->authorized();
    }

    public function isAllowed(): bool
    {
        return $this->authorized();
    }

    public function authorised(): bool
    {
        return $this->authorized();
    }

    public function isAuthorised(): bool
    {
        return $this->authorized();
    }

    public function authorise(bool|\Closure $condition = true)
    {
        return $this->authorize($condition);
    }

    public function when(bool|\Closure $condition = true)
    {
        return $this->authorize($condition);
    }
}