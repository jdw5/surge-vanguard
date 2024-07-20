<?php

namespace Conquest\Table\Columns;

use Closure;
use Conquest\Table\Columns\BaseColumn;
use Conquest\Table\Columns\Concerns\SharedCreation;
use Conquest\Table\Columns\Enums\Breakpoint;

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
