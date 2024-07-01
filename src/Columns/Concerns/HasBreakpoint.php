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
     */
    public function xs(): static
    {
        return $this->breakpoint(Breakpoint::XS);
    }

    /**
     * Set the visibility of the column to show only for sm screens
     */
    public function sm(): static
    {
        return $this->breakpoint(Breakpoint::SM);
    }

    /**
     * Set the visibility of the column to show only for md screens
     */
    public function md(): static
    {
        return $this->breakpoint(Breakpoint::MD);
    }

    /**
     * Set the visibility of the column to show only for lg screens
     */
    public function lg(): static
    {
        return $this->breakpoint(Breakpoint::LG);
    }

    /**
     * Set the visibility of the column to show only for xl screens
     */
    public function xl(): static
    {
        return $this->breakpoint(Breakpoint::XL);
    }

    /**
     * Set the visibility of the column to show only for xxl screens
     */
    public function xxl(): static
    {
        return $this->breakpoint(Breakpoint::XXL);
    }

    /**
     * Set the visibility of the column to hidden on extra small screens or larger
     */
    public function breakpoint(Breakpoint|string $breakpoint): static
    {
        $this->setBreakpoint($breakpoint);

        return $this;
    }

    /**
     * Set the breakpoint quietly.
     */
    public function setBreakpoint(Breakpoint|string $breakpoint): void
    {
        $this->breakpoint = $breakpoint instanceof Breakpoint ? $breakpoint : Breakpoint::from($breakpoint);
    }

    /**
     * Get the breakpoint
     */
    public function getBreakpoint(): ?string
    {
        return $this->breakpoint?->value ?? null;
    }
}
