<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Concerns\Formatters\FormatsBoolean;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\Concerns\HasBooleanLabels;

class BooleanColumn extends BaseColumn
{
    use FormatsBoolean;

    public function setUp(): void
    {
        $this->setType('col:bool');
        $this->boolean();
    }

    public function apply(mixed $value): mixed
    {
        $value = $this->applyTransform($value);
        return $this->formatBoolean($value);
    }
}
