<?php

namespace Conquest\Table\Columns\Concerns;

use Conquest\Table\Columns\Enums\Breakpoint;

/**
 * Disable a Tailwind-style breakpoint to show the class at.
 */
trait HasBreakpoint
{
    /** The breakpoint it should display at */
    protected ?Breakpoint $breakpoint = null;

    /**
     * Set the visibility of the column to show only for xs screens
     * 
     * @return static
     */
    public function xs(): static
    {
        return $this->breakpoint(Breakpoint::XS);
    }

    /**
     * Set the visibility of the column to show only for sm screens
     * 
     * @return static
     */
    public function sm(): static
    {
        return $this->breakpoint(Breakpoint::SM);
    }

    /**
     * Set the visibility of the column to show only for md screens
     * 
     * @return static
     */
    public function md(): static
    {
        return $this->breakpoint(Breakpoint::MD);
    }

    /**
     * Set the visibility of the column to show only for lg screens
     * 
     * @return static
     */
    public function lg(): static
    {
        return $this->breakpoint(Breakpoint::LG);
    }

    /**
     * Set the visibility of the column to show only for xl screens
     * 
     * @return static
     */
    public function xl(): static
    {
        return $this->breakpoint(Breakpoint::XL);
    }

    /**
     * Set the visibility of the column to show only for xxl screens
     * 
     * @return static
     */
    public function xxl(): static
    {
        return $this->breakpoint(Breakpoint::XXL);
    }

    /**
     * Set the visibility of the column to hidden on extra small screens or larger
     * 
     * @param Breakpoint|string $breakpoint
     * @return static
     */
    public function breakpoint(Breakpoint|string $breakpoint): static
    {
        $this->setBreakpoint($breakpoint);
        return $this;
    }

    /**
     * Set the breakpoint quietly.
     * 
     * @param Breakpoint|string $breakpoint
     * @return void
     */
    public function setBreakpoint(Breakpoint|string $breakpoint): void
    {
        $this->breakpoint = $breakpoint instanceof Breakpoint ? $breakpoint : Breakpoint::from($breakpoint);
    }

    /**
     * Get the breakpoint
     * 
     * @return string|null
     */
    public function getBreakpoint(): ?string
    {
        return $this->breakpoint?->value ?? null;
    }

}