<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

use Jdw5\Vanguard\Filters\Enums\FilterMode;
use Jdw5\Vanguard\Filters\Exceptions\InvalidMode;

trait HasMode
{
    /** Default to be exact */
    protected FilterMode $mode = FilterMode::EXACT;
    
    /**
     * Set the mode to be used.
     * 
     * @param string|FilterMode $mode
     * @return static
     * @throws InvalidMode 
     */
    public function mode(string|FilterMode $mode): static
    {
        $this->setMode($mode);
        return $this;
    }

    /**
     * Set the mode to be used quietly.
     * 
     * @param string|FilterMode $mode
     * @return void
     * @throws InvalidMode
     */
    protected function setMode(string|FilterMode $mode): void
    {
        if ($mode instanceof FilterMode) {
            $this->mode = $mode;
        } 
        else {
            try {
                $this->mode = FilterMode::from($mode);
            } catch (\Exception $e) {
                throw InvalidMode::make($mode);
            }
        }
    }

    /**
     * Set the mode to be 'exact'.
     * 
     * @return static
     */
    public function exact(): static
    {
        return $this->mode(FilterMode::EXACT);
    }

    /**
     * Set the mode to be 'loose'.
     * 
     * @return static
     */
    public function loose(): static
    {
        return $this->mode(FilterMode::LOOSE);
    }

    /**
     * Set the mode to be 'begins with'.
     * 
     * @return static
     */
    public function beginsWith(): static
    {
        return $this->mode(FilterMode::BEGINS_WITH);
    }

    /**
     * Set the mode to be 'ends with'.
     * 
     * @return static
     */
    public function endsWith(): static
    {
        $this->mode = FilterMode::ENDS_WITH;
        return $this;
    }

    /**
     * Retrieve the mode property.
     * 
     * @return FilterMode
     */
    public function getMode(): FilterMode
    {
        return $this->mode;
    }
}