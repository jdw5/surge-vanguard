<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Columns\Exceptions\KeyCannotBeDynamic;


trait IsPreferable
{
    /** Whether this column can be preferenced */
    protected bool $preferable = false;
    /** Whether this column is a default preference when no options are provided */
    private bool $defaultPreference = false;
    
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
            throw KeyCannotBeDynamic::make();
        }
        $this->setPreference(true);
        $this->setDefaultPreference($default);
        return $this;
    }

    /**
     * Set the preferable status for the column
     * 
     * @param bool $preferable
     */
    protected function setPreference(bool $preferable): void
    {
        $this->preferable = $preferable;
    }

    protected function setDefaultPreference(bool $default): void
    {
        $this->defaultPreference = $default;
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
     * Get whether the column is a default dynamic column
     * 
     * @return bool
     */
    public function isDefaultPreference(): bool
    {
        return $this->evaluate($this->defaultPreference);
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
        if (empty($cols)) return $this->isDefaultPreference();

        return in_array($this->getName(), $cols);
    }
}