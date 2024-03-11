<?php

namespace Jdw5\Vanguard\Concerns;

use Jdw5\Vanguard\Enums\Breakpoint;

trait IsHideable
{
    protected bool $show = true;
    protected ?Breakpoint $breakpoint = null;

    /**
     * Set the visibility of the column to hidden
     * 
     * @param bool $condition
     * @return static
     */
    public function hide(bool $condition = false): static
    {
        $this->show = $condition;      
        return $this;
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

    /**
     * Set the visibility of the column to hidden on extra small screens or larger
     * 
     * @return static
     */
    public function xs(): static
    {
        return $this->breakpoint(Breakpoint::XS);
    }

    public function sm(): static
    {
        return $this->breakpoint(Breakpoint::SM);
    }

    public function md(): static
    {
        return $this->breakpoint(Breakpoint::MD);
    }

    public function lg(): static
    {
        return $this->breakpoint(Breakpoint::LG);
    }

    public function xl(): static
    {
        return $this->breakpoint(Breakpoint::XL);
    }

    public function xxl(): static
    {
        return $this->breakpoint(Breakpoint::XXL);
    }

    public function breakpoint(Breakpoint|string $breakpoint): static
    {
        $this->breakpoint = $breakpoint instanceof Breakpoint ? $breakpoint : Breakpoint::tryFrom($breakpoint);
        return $this;
    }

    public function getBreakpoint(): string|null
    {
        return $this->breakpoint->value ?? null;
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
