<?php

namespace Jdw5\Vanguard\Concerns;

use Closure;

/**
 * Set a label for a class.
 */
trait HasLabel
{
    protected string|Closure $label;

    /**
     * Set the label, chainable.
     * 
     * @param string|Closure $label
     * @return static
     */
    public function label(string|Closure $label): static
    {
        $this->setLabel($label);
        return $this;
    }

    /**
     * Set the label quietly.
     * 
     * @param string|Closure $label
     * @return void
     */
    protected function setLabel(string|Closure $label): void
    {
        $this->label = $label;
    }

    /**
     * Get the label.
     * 
     * @return string
     */
    public function getLabel(): string
    {
        return $this->evaluate($this->label);
    }

    public function toLabel(string|Closure $name): string
    {
        return str($this->evaluate($name))->headline()->lower()->ucfirst();
    }
}
