<?php

namespace Conquest\Table\Columns\Concerns;

use Illuminate\Support\Str;
use InvalidArgumentException;

trait HasBreakpoint
{
    protected ?string $breakpoint = null;

    public const BREAKPOINTS = ['xs', 'sm', 'md', 'lg', 'xl'];

    /**
     * @throws InvalidArgumentException
     */
    public function breakpoint(string $breakpoint): static
    {
        $this->setBreakpoint($breakpoint);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setBreakpoint(?string $breakpoint): void
    {
        if (is_null($breakpoint)) {
            return;
        }
        $breakpoint = Str::lower($breakpoint);

        if (! in_array($breakpoint, self::BREAKPOINTS)) {
            throw new InvalidArgumentException("The provided breakpoint [$breakpoint] is invalid. Please provide one of the following: ".implode(', ', self::BREAKPOINTS));
        }
        $this->breakpoint = $breakpoint;
    }

    public function getBreakpoint(): ?string
    {
        return $this->evaluate($this->breakpoint);
    }

    public function hasBreakpoint(): bool
    {
        return ! $this->lacksBreakpoint();
    }

    public function lacksBreakpoint(): bool
    {
        return is_null($this->getBreakpoint());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function xs(): static
    {
        return $this->breakpoint('xs');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function sm(): static
    {
        return $this->breakpoint('sm');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function md(): static
    {
        return $this->breakpoint('md');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function lg(): static
    {
        return $this->breakpoint('lg');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function xl(): static
    {
        return $this->breakpoint('xl');
    }
}
