<?php

namespace Conquest\Table\Columns;

use Conquest\Core\Primitive;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Table\Columns\Contracts\Columns;
use Conquest\Table\Columns\Concerns\HasBreakpoint;
use Conquest\Table\Columns\Concerns\HasAccessibility;

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