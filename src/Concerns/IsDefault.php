<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Set whether a class is the default
 */
trait IsDefault
{
    /** Always default to false */
    protected $default = false;

    /**
     * Set the class as default, chainable
     * 
     * @return static
     */
    public function default(bool $default = true): static
    {
        $this->setDefault($default);
        return $this;
    }

    /**
     * Set the default quietly
     * 
     * @param bool $default
     */
    protected function setDefault(bool $default): void
    {
        $this->default = $default;
    }

    /**
     * Get if the class is default (alias)
     * 
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->getDefault();
    }

    /**
     * Get if the class is default
     * 
     * @return bool
     */
    public function getDefault(): bool
    {
        return $this->evaluate($this->default);
    }
}