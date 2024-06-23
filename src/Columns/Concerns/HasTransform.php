<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

/**
 * Set a transform property on a class
 */
trait HasTransform
{
    /** Closure to perform transform */
    protected null|\Closure $transform = null;

    /**
     * Set the transformation function for a given value, chainable
     */
    public function transform(\Closure $callback): static
    {
        $this->setTransform($callback);
        return $this;
    }

    /**
     * Set the transformation function for a given value quietly.
     * 
     * @param \Closure $callback
     * @return void
     */
    protected function setTransform(\Closure $callback): void
    {
        $this->transform = $callback;
    }

    /**
     * Determine if the column has a transform.
     * 
     * @return bool
     */
    public function hasTransform(): bool
    {
        return !\is_null($this->transform);
    }

    /**
     * Transform the value using the given callback.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function transformUsing(mixed $value): mixed
    {
        if (! $this->hasTransform()) return $value;
        return $this->getTransformed($value);
    }

    /**
     * Get the transformed value.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function getTransformed(mixed $value): mixed
    {
        return ($this->transform)($value);
    }
}
