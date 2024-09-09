<?php

namespace Conquest\Table\Sorts\Concerns;

trait HasDirection
{
    /** Can be asc, desc or null */
    public ?string $direction = null;

    /**
     * Set the direction
     */
    public function direction(string $direction): static
    {
        $this->setDirection($direction);

        return $this;
    }

    /**
     * Set the direction quietly.
     */
    protected function setDirection(?string $direction): void
    {
        $this->direction = $direction;
    }

    /**
     * Get the direction
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * Check if the direction is not set
     */
    public function lacksDirection(): bool
    {
        return is_null($this->direction);
    }

    /**
     * Check if the direction is set
     */
    public function hasDirection(): bool
    {
        return ! $this->lacksDirection();
    }

    /**
     * Set the direction to descending
     */
    public function desc(): static
    {
        return $this->direction('desc');
    }

    /**
     * Set the direction to ascending
     */
    public function asc(): static
    {
        return $this->direction('asc');
    }
}
