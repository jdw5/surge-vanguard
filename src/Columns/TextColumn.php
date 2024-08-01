<?php

namespace Conquest\Table\Columns;

class TextColumn extends FallbackColumn
{
    public function setUp(): void
    {
        $this->setType('col:text');
    }

    public function defaultFallback(): mixed
    {
        return config('table.fallback.text', parent::defaultFallback());
    }
}
