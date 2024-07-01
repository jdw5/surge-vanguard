<?php

namespace Conquest\Table\Sorts\Concerns;

trait HasSortKey
{
    protected string $sort = 'sort';

    public static function setGlobalSortKey(string $sort): void
    {
        static::$sort = $sort;
    }

    public function getSortKey(): string
    {
        return $this->sort;
    }

    protected function setSortKey(?string $key): void
    {
        if (is_null($key)) {
            return;
        }
        $this->sort = $key;
    }
}
