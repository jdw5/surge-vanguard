<?php

namespace Conquest\Table\Pagination\Concerns;

trait HasPageName
{
    protected $pageName;

    public function getPageName(): ?string
    {
        if (isset($this->pageName)) {
            return $this->pageName;
        }

        return config('table.pagination.name', 'show');
    }

    public function setPageName(?string $key): void
    {
        if (is_null($key)) {
            return;
        }
        $this->pageName = $key;
    }
}
