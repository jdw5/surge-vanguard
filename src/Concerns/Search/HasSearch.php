<?php

namespace Conquest\Table\Concerns\Search;

trait HasSearch
{
    protected $search;

    public function getSearch(): array
    {
        if (isset($this->search)) {
            return is_array($this->search) ? $this->search : [$this->search];
        }

        return config('table.search.columns', []);
    }

    public function setSearch(string|array|null $key): void
    {
        if (is_null($key)) {
            return;
        }
        $this->search = $key;
    }

    public function hasSearch(): bool
    {
        return ! $this->lacksSearch();
    }

    public function lacksSearch(): bool
    {
        return empty($this->getSearch());
    }
}
