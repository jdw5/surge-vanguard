<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasSearch
{
    public function getSearch(): array
    {
        if (isset($this->search)) {
            return $this->search;
        }

        if (function_exists('search')) {
            return $this->search();
        }

        return [];
    }

    public function getSearchKey(): string
    {
        if (isset($this->searchKey)) {
            return $this->searchKey;
        }

        if (function_exists('searchKey')) {
            return $this->searchKey();
        }
        
        return 'q';
    }

    public function useScout(): bool
    {
        if (isset($this->useScout)) {
            return $this->useScout;
        }

        if (function_exists('useScout')) {
            return $this->useScout();
        }

        return false;
    }
}