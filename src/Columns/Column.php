<?php

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\SharedCreation;

class Column extends FallbackColumn
{
    use SharedCreation;

    public function setUp(): void
    {
        $this->setType('col');
    }
}
