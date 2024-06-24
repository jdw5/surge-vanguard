<?php

namespace Jdw5\Vanguard\Pagination\Concerns\Internal;

trait HasPaginationKey
{
    protected string $paginationKey;

    public static function setGlobalPaginationKey(string $paginationKey): void
    {
        static::$paginationKey = $paginationKey;
    }

    protected function setPaginationKey(string $paginationKey): void
    {
        $this->paginationKey = $paginationKey;
    }

    public function getPaginationKey(): string
    {
        if (isset($this->paginationKey)) {
            return $this->paginationKey;
        }

        if (function_exists('paginationKey')) {
            return $this->paginationKey();
        }

        return 'page';
    }
}