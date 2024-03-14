<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

/**
 * Trait HasTransform
 * 
 * Set a transform property on a class
 * 
 * @property \Closure|null $getValueUsing
 */
trait HasTransform
{
    protected null|\Closure $getValueUsing = null;

    /**
     * Transforms the value of the column using the given callback.
     */
    public function transform(\Closure $callback): static
    {
        $this->getValueUsing = $callback;

        return $this;
    }

    public function canTransform(): bool
    {
        return !\is_null($this->getValueUsing);
    }

    public function transformUsing(mixed $value): mixed
    {
        if (! $this->canTransform()) {
            return $value;
        }

        return $this->getTransformed($value);
    }

    public function getTransformed(mixed $value): mixed
    {
        return ($this->getValueUsing)($value);
    }
}
