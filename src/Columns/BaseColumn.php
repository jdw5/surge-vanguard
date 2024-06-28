<?php

namespace Jdw5\Vanguard\Columns;

use Conquest\Core\Primitive;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\HasLabel;
use Jdw5\Vanguard\Columns\Contracts\Columns;
use Jdw5\Vanguard\Columns\Concerns\HasBreakpoint;
use Jdw5\Vanguard\Columns\Concerns\HasAccessibility;

abstract class BaseColumn extends Primitive implements Columns
{
    use HasLabel;
    use HasAccessibility;
    use HasBreakpoint;
    use HasType;

    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'srOnly' => $this->isScreenReaderOnly(),
            'breakpoint' => $this->getBreakpoint(),
            'type' => $this->getType(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}