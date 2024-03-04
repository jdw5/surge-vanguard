<?php

namespace Jdw5\Vanguard\Refining\Sorts\Concerns;

use Jdw5\Vanguard\Refining\Sorts\InvalidSortDirection;

trait HasDirection
{
    private const DEFAULT = 'asc';
    
    public ?string $direction = null;
    protected ?string $activeDirection = null;

    public function direction(?string $direction = null): static
    {
        if (! in_array($direction, ['asc', 'desc', null]) ) {
            throw new InvalidSortDirection($direction);
        }

        $this->direction = $direction;

        return $this;
    }

    public function getDirection(): string
    {
        return $this->direction ?? self::DEFAULT;
    }

    public function activeDirection(?string $direction = null): static
    {
        if (! in_array($direction, ['asc', 'desc', null])){
            $direction = null;
        }

        $this->activeDirection = $direction;
        return $this;
    }

    public function getActiveDirection(): ?string
    {
        return $this->evaluate($this->activeDirection);
    }

    public function desc(): static
    {
        return $this->direction('desc');
    }

    public function asc(): static
    {
        return $this->direction('asc');
    }
}