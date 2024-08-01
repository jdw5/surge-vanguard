<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

class KeyColumn extends BaseColumn
{
    public function setUp(): void
    {
        $this->setType('col:key');
        $this->setKey(true);
        $this->setHidden(true);
    }

    public function apply(mixed $value): mixed
    {
        return $value;
    }
}
