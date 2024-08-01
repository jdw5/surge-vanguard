<?php

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\Formatters\FormatsBoolean;
use Conquest\Table\Columns\Concerns\Formatters\FormatsMoney;
use Conquest\Table\Columns\Concerns\Formatters\FormatsNumeric;
use Conquest\Table\Columns\Concerns\Formatters\FormatsSeparator;

class Column extends FallbackColumn
{
    use FormatsMoney;
    use FormatsNumeric;
    use FormatsBoolean;
    use FormatsSeparator;

    public function setUp(): void
    {
        $this->setType('col');
    }

    public function formatValue(mixed $value): mixed
    {
        if ($this->isBoolean()) {
            return $this->formatBoolean($value);
        }

        if ($this->isNumeric()) {
            return $this->formatNumeric($value);
        }

        if ($this->isMoney()) {
            return $this->formatMoney($value);
        }

        if ($this->hasSeparator()) {
            return $this->formatSeparator($value);
        }

        return parent::formatValue($value);
    }
}
