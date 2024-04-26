<?php

namespace Jdw5\Vanguard\Concerns;

trait IsHideable
{
    /** Whether this column shown be shown */
    protected bool $show = true;
    /** Whether this column should only be displayed for screen readers */
    protected bool $sr_only = false;

    /**
     * Set the visibility of the column to hidden
     * 
     * @return static
     */
    public function hide(): static
    {
        $this->show = false;      
        return $this;
    }

    /**
     * Set the visibility of the column to hidden (alias)
     * 
     * @return static
     */
    public function hidden(): static
    {
        return $this->hide();
    }

    /**
     * Set the visibility of the column to screen reader only
     * 
     * @return static
     */
    public function srOnly(): static
    {
        $this->sr_only = true;   
        return $this;
    }

    /**
     * Check if the column is screen reader only
     * 
     * @return bool
     */
    public function isSrOnly(): bool
    {
        return $this->sr_only;
    }

    /**
     * Set the visibility of the column to shown
     * 
     * @param bool $condition
     * @return static
     */
    public function show(bool $condition = true): static
    {
        $this->show = $condition;
        return $this;
    }

    public function isShown(): bool
    {
        return $this->show;
    }

    public function isHidden(): bool
    {
        return ! $this->show;
    }
}
