<?php

namespace Conquest\Table\Sorts\Concerns;

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