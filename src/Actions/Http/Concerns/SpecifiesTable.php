<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Http\Concerns;

use Conquest\Table\Table;

trait SpecifiesTable
{
    protected Table $table;

    public function hasTable(): bool
    {
        return isset($this->table);
    }
    
    public function lacksTable(): bool
    {
        return ! $this->hasTable();
    }

    public function getTable(): ?Table
    {
        return $this->table;
    }
}
