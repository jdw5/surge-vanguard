<?php

namespace Jdw5\Vanguard\Concerns;

use Closure;

trait HasName
{
    protected string|Closure $name;

    /**
     * Set the name, chainable.
     * 
     * @param string|Closure $name
     * @return static
     */
    public function name(string|Closure $name): static
    {
        $this->setName($name);
        return $this;
    }

    /**
     * Set the name quietly.
     * 
     * @param string|Closure $name
     * @return void
     */
    protected function setName(string|Closure $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the name
     * 
     * @return string|Closure
     */
    public function getName(): string
    {
        return $this->evaluate($this->name);
    }

    public function toName(string|Closure $label): string
    {
        return str($this->evaluate($label))->snake()->lower();
    }
}
