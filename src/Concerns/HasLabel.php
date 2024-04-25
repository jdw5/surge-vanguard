<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Set a label for a class.
 */
trait HasLabel
{
    protected mixed $label;

    /**
     * Set the label, chainable.
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
     * Set the label quietly.
     * 
     * @param mixed $label
     * @return void
     */
    protected function setLabel(mixed $label): void
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

    protected function labelise(string $name): string
    {
        return str($name)->headline()->lower()->ucfirst();
    }
}
