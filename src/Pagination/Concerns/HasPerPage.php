<?php

namespace Conquest\Table\Pagination\Concerns;

trait HasPerPage
{
    protected int|array $perPage;

    public function getPerPage(): int|array|null
    {
        if (isset($this->perPage)) {
            return $this->perPage;
        }

        if (method_exists($this, 'perPage')) {
            return $this->perPage();
        }

        return null;
    }

    public function setPerPage(int|array|null $perPage): void
    {
        if (is_null($perPage)) return;
        $this->perPage = $perPage;
    }
}
