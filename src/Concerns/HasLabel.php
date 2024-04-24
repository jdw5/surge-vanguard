<?php

namespace Jdw5\Vanguard\Concerns;

trait HasLabel
{
    protected mixed $label;

    /**
     * Chainable method for setting the label.
     * 
     * @param mixed $label
     * @return static
     */
    public function label(mixed $label): static
    {
        $this->setLabel($label);
        return $this;
    }

    /**
     * Set the label.
     * 
     * @param mixed $label
     * @return void
     */
    public function setLabel(mixed $label): void
    {
        $this->label = $label;
    }

    /**
     * Get the label.
     * 
     * @return mixed
     */
    public function getLabel(): mixed
    {
        return $this->evaluate($this->label);
    }
}
