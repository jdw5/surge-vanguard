<?php

namespace Conquest\Table\Columns;

use Conquest\Table\Concerns\Formatters\FormatsMoney;
use Conquest\Table\Concerns\Formatters\FormatsNumeric;

class NumericColumn extends FallbackColumn
{
    use FormatsMoney;
    use FormatsNumeric;

    public function setUp(): void
    {
        $this->setType('col:numeric');
    }

    public function defaultFallback(): mixed
    {
        return config('table.fallback.numeric', parent::defaultFallback());
    }
}

