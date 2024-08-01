<?php

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\Formatters\FormatsBoolean;
use Conquest\Table\Concerns\Formatters\FormatsMoney;
use Conquest\Table\Concerns\Formatters\FormatsNumeric;

class Column extends FallbackColumn
{
    use FormatsMoney;
    use FormatsNumeric;
    use FormatsBoolean;

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

        return parent::formatValue($value);
    }
}
