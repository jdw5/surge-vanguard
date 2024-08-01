<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait FormatsBoolean
{
    use HasFalseLabel;
    use HasTruthLabel;

    protected bool $boolean = false;

    public function boolean(string|Closure|null $true = null, string|Closure|null $false = null): static
    {
        $this->boolean = true;
        $this->setTruthLabel($true);
        $this->setFalseLabel($false);

        return $this;
    }

    public function formatsBoolean(): bool
    {
        return $this->boolean;
    }

    public function formatBoolean(mixed $value)
    {
        return $this->evaluate($value) ? $this->getTruthLabel() : $this->getFalseLabel();
    }
}
