<?php

declare(strict_types=1);

namespace Conquest\Table\Pagination\Concerns;

trait HasPaginationType
{
    protected $paginationType;

    public const PAGINATION_TYPES = [
        'paginate',
        'cursor',
        null
    ];

    public function setPaginationType(?string $paginationType): void
    {
        $this->paginationType = $paginationType;
    }

    public function getPaginateType(): ?string
    {
        if (isset($this->paginationType)) {
            return $this->paginationType;
        }

        if (method_exists($this, 'paginateType')) {
            return $this->paginateType();
        }

        return 'paginate';
    }
}
