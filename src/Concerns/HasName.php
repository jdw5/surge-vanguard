<?php

namespace Jdw5\Vanguard\Concerns;

trait HasName
{
    protected string $name;

    /**
     * Set the name, chainable.
     * 
     * @param string $name
     * @return static
     */
    public function name(string $name): static
    {
        $this->setName($name);
        return $this;
    }

    /**
     * Set the name quietly.
     * 
     * @param string $name
     * @return void
     */
    protected function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
