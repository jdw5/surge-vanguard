<?php

namespace Conquest\Table\Export;

use Closure;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Primitive;

class Export extends Primitive implements Exports
{
    use HasLabel;

    public function __construct(
        string $label,
        ?string $filename = null,
        // type = null,
        Closure|bool $authorize = true,
    ) {
        $this->setLabel($label);
        // $this-
    }

    public function export()
    {
        return [];
    }

    public function toArray(): array
    {
        return [
        ];
    }
}
