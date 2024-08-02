<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

class TextColumn extends FallbackColumn
{
    public function setUp(): void
    {
        $this->setType('text');
    }

    public function defaultFallback(): mixed
    {
        return config('table.fallback.text', parent::defaultFallback());
    }
}
