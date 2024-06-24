<?php

namespace Jdw5\Vanguard\Concerns;

trait HasDisplay
{
    /** Whether this column shown be shown */
    protected bool $show = true;

    protected function setShow(bool $show): void
    {
        $this->show = $show;
    }

    /**
     * Set the visibility of the column to hidden
     * 
     * @return static
     */
    public function hide(): static
    {
        $this->setShow(false);    
        return $this;
    }

    public function hidden(): static
    {
        return $this->hide();
    }

    public function dontDisplay(): static
    {
        return $this->hide();
    }

    /**
     * Set the visibility of the column to shown
     * 
     * @param bool $condition
     * @return static
     */
    public function show(bool $condition = true): static
    {
        $this->setShow($condition);
        return $this;
    }

    public function shown(): static
    {
        return $this->show(true);
    }

    public function display(bool $condition = true): static
    {
        return $this->show(true);
    }

    public function isShown(): bool
    {
        return $this->show;
    }

    public function isHidden(): bool
    {
        return !$this->show;
    }
}
