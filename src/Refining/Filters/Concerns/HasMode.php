<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

use Jdw5\Vanguard\Refining\Filters\Enums\FilterMode;

trait HasMode
{
    protected FilterMode $mode = FilterMode::EXACT;
    
    public function mode(string|FilterMode $mode): static
    {
        if ($mode instanceof FilterMode) {
            $this->mode = $mode;
        } else {
            try {
                $this->mode = FilterMode::from($mode);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException('Invalid mode');
            }
        }
        return $this;
    }

    public function exact(): static
    {
        $this->mode = FilterMode::EXACT;
        return $this;
    }

    public function loose(): static
    {
        $this->mode = FilterMode::LOOSE;
        return $this;
    }

    public function beginsWith(): static
    {
        $this->mode = FilterMode::BEGINS_WITH;
        return $this;
    }

    public function endsWith(): static
    {
        $this->mode = FilterMode::ENDS_WITH;
        return $this;
    }

    public function getMode(): FilterMode
    {
        return $this->mode;
    }
}