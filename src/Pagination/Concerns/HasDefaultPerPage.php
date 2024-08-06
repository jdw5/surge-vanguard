<?php

declare(strict_types=1);

namespace Conquest\Table\Pagination\Concerns;

trait HasDefaultPerPage
{
    /**
     * @var int
     */
    protected $defaultPerPage;

    public function getDefaultPerPage(): int
    {
        if (isset($this->defaultPerPage)) {
            return $this->defaultPerPage;
        }

        if (method_exists($this, 'defaultPerPage')) {
            return $this->defaultPerPage();
        }

        return config('table.pagination.default', 10);
    }

    public function setDefaultPerPage(?int $perPage): void
    {
        if (is_null($perPage)) {
            return;
        }
        $this->defaultPerPage = $perPage;
    }
}
