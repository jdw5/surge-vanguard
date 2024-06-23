<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasProcess
{
    /** Determine whether the process has been completed */
    private bool $processed = false;

    abstract public function pipeline(): void;

    /**
     * Process the table
     * 
     * @return void
     */
    public function process(): void
    {
        if ($this->hasBeenProcessed()) {
            return;
        }

        $this->pipeline();
        
        $this->processed = true;
    }

    /**
     * Determine whether the table has been processed
     * 
     * @return bool
     */
    public function hasBeenProcessed(): bool
    {
        return $this->evaluate($this->processed);
    }

    /**
     * Set the process status
     * 
     * @param bool $processed
     * @return void
     
     */
    public function setProcessed(bool $processed): void
    {
        $this->processed = $processed;
    }

    /**
     * Determine whether the table has not been processed
     * 
     * @return bool
     */
    public function hasNotBeenProcessed(): bool
    {
        return ! $this->hasBeenProcessed();
    }


}