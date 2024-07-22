<?php

namespace Conquest\Table\Sorts\Concerns;

trait HasDirection
{
    /** Can be asc, desc or null */
    public ?string $direction = null;

    /**
     * Set the direction
     *
     * @param  string  $direction
     */
    public function direction(string $direction): static
    {
        $this->setDirection($direction);

        return $this;
    }

    /**
     * Set the direction quietly.
     * 
     * @param string|null $direction
     */
    protected function setDirection(?string $direction): void
    {
        $this->direction = $direction;
    }

    /**
     * Get the direction
     * 
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * Set the direction to descending
     * 
     * @return static
     */
    public function desc(): static
    {
        return $this->direction('desc');
    }

    /**
     * Set the direction to ascending
     * 
     * @return static
     */
    public function asc(): static
    {
        return $this->direction('asc');
    }

    public function lacksDirection(): bool
    {
        return is_null($this->direction);
    }

    public function hasDirection(): bool
    {
        return !$this->lacksDirection();
    }
}
