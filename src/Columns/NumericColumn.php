<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\IsSearchable;
use Conquest\Table\Columns\Concerns\Formatters\FormatsMoney;
use Conquest\Table\Columns\Concerns\Formatters\FormatsNumeric;

class NumericColumn extends FallbackColumn
{
    use FormatsMoney;
    use FormatsNumeric;
    use IsSearchable;

    public function setUp(): void
    {
        $this->setType('numeric');
    }

    public function defaultFallback(): mixed
    {
        return config('table.fallback.numeric', parent::defaultFallback());
    }

    public function formatValue(mixed $value): mixed
    {
        if ($this->formatsNumeric()) {
            return $this->formatNumeric($value);
        }

        if ($this->formatsMoney()) {
            return $this->formatMoney($value);
        }

        return parent::formatValue($value);
    }
}
