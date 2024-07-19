<?php

namespace Conquest\Table\Concerns\Search;

use Illuminate\Support\Facades\Request;

trait HasSearchKey
{
    protected $searchKey;

    public function getSearchKey(): string
    {
        if (isset($this->searchKey)) {
            return $this->searchKey;
        }

        return config('table.search.key', 'q');
    }

    public function setSearchKey(string|null $key): void
    {
        if (is_null($key)) return;
        $this->searchKey = $key;
    }

    public function getSearchFromRequest(): ?string
    {
        return Request::input($this->getSearchKey(), null);
    }
}
