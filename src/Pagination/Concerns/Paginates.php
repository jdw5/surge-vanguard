<?php

namespace Conquest\Table\Pagination\Concerns;

use Closure;
use Conquest\Table\Pagination\Pagination;

trait Paginates
{
    use HasDefaultPerPage;
    use HasPageName;
    use HasPerPage;
    use HasPaginationType;
    use HasShowKey;


    protected function setDefaultPagination(int $defaultPagination): void
    {
        $this->defaultPagination = $defaultPagination;
    }

    public function getPaginationCount(): int|array
    {
        return $this->getPerPage() ?? $this->getDefaultPerPage();
    }

    public function setPagination(int|array|null $pagination): void
    {
        if (is_null($pagination)) return;
        $this->pagination = $pagination;
    }

    public function getPagination(int $active = null): array
    {
        if (! is_array($p = $this->getPaginationCount())) {
            return [Pagination::make($p, true)];
        }

        $options = [];

        foreach ($p as $count) {
            $options[] = Pagination::make($count, $count === $active);
        }

        return $options;
    }

    public function usePerPage(): int
    {
        $c = $this->getPaginationCount();
        if (is_int($c)) return $c;
        if (in_array($q = $this->getPerPageFromRequest(), $c)) return $q;
        return $this->getDefaultPerPage();
    }


}