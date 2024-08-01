<?php

declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns\Formatters;

use Closure;

trait FormatsBoolean
{
    use HasTruthLabel;
    use HasFalseLabel;

    protected bool $boolean = false;

    public function boolean(string|Closure $truthLabel = null, string|Closure $falseLabel = null): static
    {
        $this->boolean = true;
        $this->setTruthLabel($truthLabel);
        $this->setFalseLabel($falseLabel);
        return $this;
    }
    
    public function isBoolean(): bool
    {
        return $this->boolean;
    }

    public function isNotBoolean(): bool
    {
        return  ! $this->isBoolean();
    }

    public function formatBoolean(mixed $value)
    {
        if ($this->isBoolean()) {
            return !!$value ? $this->getTruthLabel() : $this->getFalseLabel();
        }

        return $value;
    }
}
