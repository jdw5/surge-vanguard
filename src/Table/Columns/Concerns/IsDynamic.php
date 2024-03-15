<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

use Jdw5\Vanguard\Table\Columns\Exceptions\KeyCannotBeDynamic;


trait IsDynamic
{
    // Whether the column has dynamics enabled
    protected bool $dynamicEnabled = false;
    // Whether this is a default if no preference is provided
    protected bool $defaultCol = false;
    // Whether the preference is active
    private bool $dynamicActive = false;
    
    /**
     * Enable dynamics for the column
     * 
     * @param bool $default
     * @return static
     * @throws KeyCannotBeDynamic
     */
    public function dynamic(bool $default = false): static
    {
        if ($this->isKey()) {
            throw KeyCannotBeDynamic::invalid();
        }

        $this->dynamicEnabled = true;
        $this->defaultCol = $default;
        return $this;
    }

    /**
     * Check if the column has dynamics enabled
     * 
     * @return bool
     */
    public function dynamicEnabled(): bool
    {
        return $this->evaluate($this->dynamicEnabled);
    }

    /**
     * Check if the dynamic column should be applied
     * 
     * @return bool
     */
    public function getDynamicActive(): bool
    {
        return $this->evaluate($this->dynamicActive);
    }

    /**
     * Get whether the column is a default dynamic column
     * 
     * @return bool
     */
    public function getDefaultDynamic(): bool
    {
        return $this->evaluate($this->defaultCol);
    }

    /**
     * Determine if the column should be dynamically shown
     * 
     * @param bool $inQuery
     * @return bool
     */
    public function shouldBeDynamicallyShown(bool $inQuery = false): bool
    {
        if ($this->dynamicEnabled()) {
            return $inQuery ? $this->getDynamicActive() : $this->getDefaultDynamic();
        }

        // Not enabled and should always be shown
        return true;
    }

    /**
     * Set the dynamic active state
     * 
     * @param bool $active
     * @return static
     */
    public function dynamicActive(bool $active = true): static
    {
        $this->dynamicActive = $active;
        return $this;
    }
}