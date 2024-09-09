<?php

declare(strict_types=1);

namespace Conquest\Table\Pagination\Concerns;

use Illuminate\Support\Facades\Request;

trait HasShowKey
{
    /**
     * @var string
     */
    protected $showKey;

    public function getShowKey(): string
    {
        if (isset($this->showKey)) {
            return $this->showKey;
        }

        if (method_exists($this, 'showKey')) {
            return $this->showKey();
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

    public function getPerPageFromRequest(): int
    {
        return Request::integer($this->getShowKey(), 0);
    }
}
