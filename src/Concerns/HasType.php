<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Set a type property on a class
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
        $this->setType($type);
        return $this;
    }

    /**
     * Set the type property quietly.
     * 
     * @param string|\Closure $type
     * @return void
     */
    protected function setType(string|\Closure $type): void
    {
        $this->type = $type;
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
