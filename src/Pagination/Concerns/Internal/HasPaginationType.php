<?php

namespace Jdw5\Vanguard\Pagination\Concerns\Internal;

use Jdw5\Vanguard\Pagination\Enums\PaginationType;

trait HasPaginationType
{
    protected PaginationType|string $paginationType;

    protected function setPaginationType(PaginationType|string $paginationType): void
    {
        if ($paginationType instanceof PaginationType) {
            $this->paginationType = $paginationType;
            return;
        }

        $this->paginationType = PaginationType::from($paginationType);
    }

    public function getPaginateType(): PaginationType|string
    {
        if (isset($this->paginationType)) {
            return $this->paginationType;
        }

        if (function_exists('paginateType')) {
            return $this->paginateType();
        }

        return PaginationType::SIMPLE;
    }

    public function getPaginateTypeString(): string
    {
        if ($this->getPaginateType() instanceof PaginationType) {
            return $this->getPaginateType()->value;
        }

        return $this->getPaginateType();
    }
}