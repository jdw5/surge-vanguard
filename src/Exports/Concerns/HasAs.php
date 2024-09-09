<?php

namespace Conquest\Table\Exports\Concerns;

use Maatwebsite\Excel\Excel;

trait HasAs
{
    protected ?Excel $as = null;

    public function setAs(Excel $as): void
    {
        $this->as = $as;
    }

    public function getAs(): ?Excel
    {
        return $this->as;
    }
}
