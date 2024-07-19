<?php

namespace Conquest\Table\Sorts\Concerns;

use Illuminate\Support\Facades\Request;

trait HasSort
{
    protected $sort;

    public function getSortKey(): string
    {
        if (isset($this->sort)) {
            return $this->sort;
        }
        
        return config('table.sorting.sort_key', 'sort');
    }

    public function setSortKey(string|null $sortKey): void
    {
        if (is_null($sortKey)) return;
        $this->sort = $sortKey;
    }

    public function getSort(): ?string
    {
        return Request::input($this->getSortKey(), null);
    }
}
