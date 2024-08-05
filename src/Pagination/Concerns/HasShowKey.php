<?php

namespace Conquest\Table\Pagination\Concerns;

use Illuminate\Support\Facades\Request;

trait HasShowKey
{
    protected $showKey;

    public function getShowKey(): string
    {
        if (isset($this->showKey)) {
            return $this->showKey;
        }

        return config('table.pagination.key', 'show');
    }

    public function setShowKey(?string $key): void
    {
        if (is_null($key)) {
            return;
        }
        $this->showKey = $key;
    }

    public function getPerPageFromRequest(): ?int
    {
        return (int) Request::input($this->getShowKey(), null);
    }
}
