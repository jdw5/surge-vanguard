<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\IsSearchable;

class TextColumn extends FallbackColumn
{
    use IsSearchable;

    public function setUp(): void
    {
        $this->setType('text');
    }

    public function defaultFallback(): mixed
    {
        return config('table.fallback.text', parent::defaultFallback());
    }
}
