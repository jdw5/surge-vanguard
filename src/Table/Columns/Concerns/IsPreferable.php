<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

use Jdw5\Vanguard\Table\Columns\Exceptions\KeyCannotBeDynamic;


trait IsPreferable
{
    protected bool $preferable = false;
    private bool $default = false;
    private bool $active = false;
    
    /**
     * Enable dynamics for the column
     * 
     * @param bool $default
     * @return static
     * @throws KeyCannotBeDynamic
     */
    public function preference(bool $default = false): static
    {
        if ($this->isKey()) {
            throw KeyCannotBeDynamic::invalid();
        }
        $this->preferable = true;
        $this->default = $default;
        return $this;
    }

    /**
     * Check if the column has dynamics enabled
     * 
     * @return bool
     */
    public function isPreferable(): bool
    {
        return $this->evaluate($this->preferable);
    }

    /**
     * Check if the dynamic column should be applied
     * 
     * @return bool
     */
    public function isBeingPreferenced(): bool
    {
        return $this->evaluate($this->active);
    }

    /**
     * Get whether the column is a default dynamic column
     * 
     * @return bool
     */
    public function isDefaultPreference(): bool
    {
        return $this->evaluate($this->default);
    }

    /**
     * Determine if the column should be dynamically shown
     * 
     * @param bool $inQuery
     * @return bool
     */
    public function shouldBeDynamicallyShown(array $cols): bool
    {
        if (!$this->isPreferable()) return true;
        if (count($cols) === 0) return $this->isDefaultPreference();

        return in_array($this->getName(), $cols);
    }

    /**
     * Set the dynamic active state
     * 
     * @param bool $active
     * @return static
     */
    public function activePreference(bool $active = true): static
    {
        $this->active = $active;
        return $this;
    }

    public function isActivePreference(): bool
    {
        return $this->evaluate($this->active);
    }
}