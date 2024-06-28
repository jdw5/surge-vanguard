<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

/**
 * Set a transform property on a class
 */
trait HasTransform
{
    /** Closure to perform transform */
    protected Closure $transform = null;

    /**
     * Set the transformation function for a given value, chainable
     */
    public function transform(Closure $callback): static
    {
        $this->setTransform($callback);
        return $this;
    }

    public function cast(Closure $callback): static
    {
        return $this->transform($callback);
    }

    public function as(Closure $callback): static
    {
        return $this->transform($callback);
    }

    /**
     * Set the transformation function for a given value quietly.
     * 
     * @param Closure $callback
     * @return void
     */
    protected function setTransform(Closure|null $callback): void
    {
        if (is_null($callback)) return;
        $this->transform = $callback;
    }

    /**
     * Determine if the column has a transform.
     * 
     * @return bool
     */
    public function hasTransform(): bool
    {
        return !is_null($this->transform);
    }

    /**
     * Transform the value using the given callback.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function transformUsing(mixed $value): mixed
    {
        if (!$this->hasTransform()) return $value;
        return $this->performTransform($value);
    }

    /**
     * Get the transformed value.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function performTransform(mixed $value): mixed
    {
        return ($this->transform)($value);
    }
}
