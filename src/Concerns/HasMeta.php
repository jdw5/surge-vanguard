<?php

namespace Conquest\Table\Concerns;

use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait HasMeta
{
    protected array $meta = [];

    protected function setMeta(?array $meta): void
    {
        if (empty($meta)) {
            return;
        }
        $this->meta = $meta;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function hasMeta(): bool
    {
        return ! empty($this->meta);
    }

    /**
     * Generate the meta for an unpaginated collection.
     */
    public function getCollectionMeta(Collection $collection): array
    {
        return [
            'empty' => $collection->isEmpty(),
            'show' => $collection->isNotEmpty(),
        ];
    }

    /**
     * Generate the meta for a cursor paginated collection.
     */
    public function getCursorMeta(CursorPaginator $paginator): array
    {
        return [
            'per_page' => $paginator->perPage(),
            'next_cursor' => $paginator->nextCursor()?->encode(),
            'prev_cusor' => $paginator->previousCursor()?->encode(),
            'next_url' => $paginator->nextPageUrl(),
            'prev_url' => $paginator->previousPageUrl(),
            'show' => $paginator->hasPages(),
            'empty' => $paginator->isEmpty(),
        ];
    }

    /**
     * Generate the meta for a length aware paginated collection.
     */
    public function getPaginateMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem() ?? 0,
            'to' => $paginator->lastItem() ?? 0,
            'total' => $paginator->total(),
            'links' => $paginator->onEachSide(1)->linkCollection()->splice(1, -1)->values()->toArray(),
            'first_url' => $paginator->url(1),
            'last_url' => $paginator->url($paginator->lastPage()),
            'next_url' => $paginator->nextPageUrl(),
            'prev_url' => $paginator->previousPageUrl(),
            'show' => $paginator->hasPages(),
            'empty' => $paginator->isEmpty(),
        ];
    }
}
