<?php

namespace Conquest\Table\Columns;

class KeyColumn extends BaseColumn
{
    public function setUp(): void
    {
        $this->setType('col:bool');
        $this->setKey(true);
        $this->setHidden(true);
    }

    public function apply(mixed $value): mixed
    {
        return $value;
    }
}
