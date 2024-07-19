<?php

namespace Conquest\Table\Concerns\Search;

trait UsesScout
{
    protected $scout;

    public function usesScout(): bool
    {
        if (isset($this->scout)) {
            return $this->scout;
        }

        return config('table.search.scout', false);
    }

    public function setScout(bool|null $usesScout): void
    {
        if (is_null($usesScout)) return;
        $this->scout = $usesScout;
    }
}
