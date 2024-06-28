<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

trait HasSearch
{
    protected array $search;
    protected string $searchKey = 'q';
    protected bool $useScout;

    public static function setGlobalSearchKey(string $key): void
    {
        static::$searchKey = $key;
    }

    protected function setSearch(string|array|null $search): void
    {
        if (is_null($search)) return;
        $this->search = $search;
    }

    public function getSearch(): array
    {
        if (isset($this->search)) {
            return is_array($this->search) ? $this->search : [$this->search];
        }

        if (function_exists('search')) {
            return $this->search();
        }

        return [];
    }

    public function getSearchKey(): string
    {
        if (function_exists('searchKey')) {
            return $this->searchKey();
        }

        return $this->searchKey;
    }

    public function getSearchTerm(Request $request): string|null
    {
        return $request->query($this->getSearchKey());
    }

    protected function usesScout(): bool
    {
        if (isset($this->useScout)) {
            return $this->useScout;
        }

        if (function_exists('useScout')) {
            return $this->useScout();
        }

        return false;
    }

    public function applySearch(Builder|QueryBuilder $query, string|null $term): void
    {
        if (empty($term)) return;

        if ($this->useScout()) {
            $query->search($term);
        }

        $query->whereAny($this->getSearchColumns(), 'LIKE', "%$term%");

    }
}