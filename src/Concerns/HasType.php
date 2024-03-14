<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Trait HasType
 * 
 * Set a type property on a class
 * 
 * @property string|\Closure $type
 */
trait HasType
{
    protected string|\Closure $type;

    /**
     * Set the type property
     * 
     * @param string|\Closure $type
     * @return static
     */
    public function type(string|\Closure $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the class type
     * 
     * @return string
     */
    public function getType(): string
    {
        return $this->evaluate($this->type);
    }
}
