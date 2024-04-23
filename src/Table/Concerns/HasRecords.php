<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasRecords
{
    protected mixed $records = null;

    public function getRecords(bool $force = false): mixed
    {
        if ($force) {
            $this->records = $this->pipelineWithRecords();
        }
        return $this->records ??= $this->pipelineWithRecords();
    }

    public function getFirstRecord(): mixed
    {
        return $this->getRecords()->first();
    }

    abstract protected function pipelineWithRecords(): mixed;
}