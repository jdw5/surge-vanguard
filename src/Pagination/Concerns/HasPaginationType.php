<?php

namespace Conquest\Table\Pagination\Concerns;

use Conquest\Table\Pagination\Enums\PaginationType;

trait HasPaginationType
{
    protected PaginationType $paginationType;

    protected function setPaginationType(PaginationType|string $paginationType): void
    {
        if ($paginationType instanceof PaginationType) {
            $this->paginationType = $paginationType;

            return;
        }

        $this->paginationType = PaginationType::from($paginationType);
    }

    public function getPaginateType(): PaginationType
    {
        if (isset($this->paginationType)) {
            return $this->paginationType;
        }

        if (method_exists($this, 'paginateType')) {
            return $this->paginateType();
        }

        return PaginationType::SIMPLE;
    }

    public function getPaginateTypeString(): string
    {
        return $this->getPaginateType()->value;
    }
}
