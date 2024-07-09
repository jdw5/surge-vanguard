<?php

namespace Conquest\Table\Sorts\Concerns;

trait HasDirection
{
    /** Can be asc, desc or null */
    public ?string $direction = null;

    /**
     * Set the direction
     *
     * @param  string|null  $direction
     */
    public function direction(string $direction): static
    {
        $this->setDirection($direction);

        return $this;
    }

    public function dir(string $direction): static
    {
        return $this->direction($direction);
    }

    /**
     * Set the direction quietly.
     */
    protected function setDirection(?string $direction): void
    {
        $this->direction = $this->sanitiseOrder($direction);
    }

    /**
     * Get the direction
     */
    public function getDirection(): ?string
    {
        return $this->direction;
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
