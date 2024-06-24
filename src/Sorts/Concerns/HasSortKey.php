<?php

namespace Jdw5\Vanguard\Sorts\Concerns;

trait HasSortKey
{
    protected string $sort = 'sort';

public static function setGlobalSortKey(string $sort): void
    {
        static::$sort = $sort;
    }

    public function getOrderKey(): string
    {
        return $this->sort;
    }
}