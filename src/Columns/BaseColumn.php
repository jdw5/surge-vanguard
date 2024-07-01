<?php

namespace Conquest\Table\Columns;

use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasProperty;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Primitive;
use Conquest\Table\Columns\Concerns\HasAccessibility;
use Conquest\Table\Columns\Concerns\HasBreakpoint;
use Conquest\Table\Columns\Contracts\Columns;

abstract class BaseColumn extends Primitive implements Columns
{
    use HasAccessibility;
    use HasBreakpoint;
    use HasLabel;
    use HasProperty;
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
}
