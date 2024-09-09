<?php

declare(strict_types=1);

namespace Conquest\Table\Pagination\Concerns;

use Conquest\Table\Pagination\Enums\Paginator;

trait HasPaginator
{
    /**
     * @var Paginator
     */
    protected $paginator;

    public function setPaginator(Paginator|string|null $paginator): void
    {
        if (is_null($paginator)) {
            return;
        }
        $this->paginator = $this->resolvePaginator($paginator);
    }

    public function getPaginator(): Paginator
    {
        if (isset($this->paginator)) {
            return $this->resolvePaginator($this->paginator);
        }

        if (method_exists($this, 'paginator')) {
            $result = $this->paginator();

            return $this->resolvePaginator($result);
        }

        return Paginator::Page;
    }

    private function resolvePaginator(string|Paginator $paginator): Paginator
    {
        return $paginator instanceof Paginator ? $paginator : Paginator::from($paginator);
    }
}
