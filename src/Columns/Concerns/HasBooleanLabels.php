<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait HasBooleanLabels
{
    protected string|Closure $truthLabel = 'Active';

    protected string|Closure $falseLabel = 'Inactive';

    public function truthLabel(string|Closure $label): static
    {
        $this->setTruthLabel($label);

        return $this;
    }

    public function falseLabel(string|Closure $label): static
    {
        $this->setFalseLabel($label);

        return $this;
    }

    protected function setTruthLabel(string|Closure|null $label): void
    {
        if (is_null($label)) return;
        $this->truthLabel = $label;
    }

    protected function setFalseLabel(string|Closure|null $label): void
    {
        if (is_null($label)) return;
        $this->falseLabel = $label;
    }

    public function getTruthLabel(): string
    {
        return $this->evaluate($this->truthLabel);
    }

    public function getFalseLabel(): string
    {
        return $this->evaluate($this->falseLabel);
    }

    public function getTruthLabels(): array
    {
        return [
            'true' => $this->getTruthLabel(),
            'false' => $this->getFalseLabel(),
        ];
    }
}
