<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\Concerns\SharedCreation;

class TextColumn extends FallbackColumn
{
    use SharedCreation;
    
    public function setUp(): void
    {
        $this->setType('col:text');
    }

    protected function defaultFallback(): mixed
    {
        return config('table.fallback.text', parent::defaultFallback());
    }
}
