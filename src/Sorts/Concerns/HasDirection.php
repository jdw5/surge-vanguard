<?php

namespace Jdw5\Vanguard\Refining\Sorts\Concerns;

use Jdw5\Vanguard\Refining\Contracts\Sorts;

trait HasDirection
{    
    /** Can be asc, desc or null */
    public ?string $direction = null;

    /**
     * Set the direction
     * 
     * @param string|null $direction
     * @return static
     */
    public function direction(?string $direction = null): static
    {
        if (!\in_array($direction, ['asc', 'desc', null]) ) $direction = 'asc';

        $this->setDirection($direction);
        return $this;
    }

    /**
     * Set the direction quietly.
     * 
     * @param string|null $direction
     * @return void
     */
    public function setDirection(?string $direction): void
    {
        $this->direction = $direction;
    }

    /**
     * Get the direction
     * 
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction ?? Sorts::DEFAULT_DIRECTION;
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
}