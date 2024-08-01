<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\Formatters\FormatsBoolean;

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
