<?php

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\SharedCreation;

class NumericColumn extends FallbackColumn
{
    use SharedCreation;

    public function setUp(): void
    {
        $this->setType('col:numeric');
    }
    
    protected function defaultFallback(): mixed
    {
        return config('table.fallback.numeric', parent::defaultFallback());
    }
}

