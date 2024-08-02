<?php

declare(strict_types=1);

namespace Conquest\Table\Columns;

use Conquest\Table\Columns\Concerns\Formatters\FormatsBoolean;
use Conquest\Table\Columns\Concerns\Formatters\FormatsMoney;
use Conquest\Table\Columns\Concerns\Formatters\FormatsNumeric;
use Conquest\Table\Columns\Concerns\Formatters\FormatsSeparator;

class Column extends FallbackColumn
{
    use FormatsBoolean;
    use FormatsMoney;
    use FormatsNumeric;
    use FormatsSeparator;

    public function formatValue(mixed $value): mixed
    {
        if ($this->formatsBoolean()) {
            return $this->formatBoolean($value);
        }

        if ($this->formatsNumeric()) {
            return $this->formatNumeric($value);
        }

        if ($this->formatsMoney()) {
            return $this->formatMoney($value);
        }

        if ($this->formatsSeparator()) {
            return $this->formatSeparator($value);
        }

        return parent::formatValue($value);
    }
}
