<?php

namespace Conquest\Table\Pagination\Concerns;

trait HasDefaultPerPage
{
    protected int $defaultPerPage;

    public function getDefaultPerPage(): int
    {
        if (isset($this->defaultPerPage)) {
            return $this->defaultPerPage;
        }

        return config('table.pagination.default', 10);
    }

    public function setDefaultPerPage(int|null $perPage): void
    {
        if (is_null($perPage)) return;
        $this->defaultPerPage = $perPage;
    }
}
