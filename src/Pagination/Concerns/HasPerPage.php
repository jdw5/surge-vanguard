<?php

declare(strict_types=1);

namespace Conquest\Table\Pagination\Concerns;

trait HasPerPage
{
    /**
     * @var int|array
     */
    protected $perPage;

    /**
     * @return int|array<int>|null
     */
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

    /**
     * @param  int|array<int>|null  $perPage
     */
    public function setPerPage(int|array|null $perPage): void
    {
        if (is_null($perPage)) {
            return;
        }
        $this->perPage = $perPage;
    }
}
