<?php

namespace Conquest\Table\Sorts\Concerns;

use Conquest\Table\Sorts\Concerns\HasOrderKey;

trait HasDirection
{    
    use HasOrderKey {
        sanitiseOrder as public;
    }

    /** Can be asc, desc or null */
    public ?string $direction = null;

    /**
     * Set the direction
     * 
     * @param string|null $direction
     * @return static
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
     * 
     * @param string|null $direction
     * @return void
     */
    protected function setDirection(string|null $direction): void
    {
        $this->direction = $this->sanitiseOrder($direction);
    }

    /**
     * Get the direction
     * 
     * @return string
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
}