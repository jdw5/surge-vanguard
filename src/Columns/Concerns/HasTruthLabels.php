<?php

namespace Conquest\Table\Columns\Concerns;

use Closure;

trait HasTruthLabels
{
    protected string|Closure $truthLabel = 'Yes';
    protected string|Closure $falseLabel = 'No';

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

    public function ifTrue(string|Closure $label): static
    {
        return $this->truthLabel($label);
    }

    public function ifFalse(string|Closure $label): static
    {
        return $this->falseLabel($label);
    }

    public function whenTrue(string|Closure $label): static
    {
        return $this->truthLabel($label);
    }

    public function whenFalse(string|Closure $label): static
    {
        return $this->falseLabel($label);
    }

    protected function setTruthLabel(string|Closure $label): void
    {
        $this->truthLabel = $label;
    }

    protected function setFalseLabel(string|Closure $label): void
    {
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

    public static function setGlobalTruthLabel(string|Closure $label): void
    {
        static::$truthLabel = $label;
    }

    public static function setGlobalFalseLabel(string|Closure $label): void
    {
        static::$falseLabel = $label;
    }
}